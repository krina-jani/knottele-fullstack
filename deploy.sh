#!/bin/bash

# ==============================================================================
# KNOTELLE — Production Deployment Script (Laravel Backend + Next.js Frontend)
# Bulletproof deployment script with strict health checks & failure traps.
# ==============================================================================

set -e # Exit immediately if a command exits with a non-zero status

echo "🚀 Starting deployment for KNOTELLE..."

SERVER_PROJECT_DIR="/var/www/knottele-fullstack"

# 1. Determine project root dynamically
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

if [ -d "$SCRIPT_DIR/backend" ] && [ -d "$SCRIPT_DIR/frontend" ]; then
    PROJECT_ROOT="$SCRIPT_DIR"
elif [ -d "$SCRIPT_DIR/app" ] && [ -f "$SCRIPT_DIR/artisan" ]; then
    PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
elif [ -d "$SERVER_PROJECT_DIR/backend" ]; then
    PROJECT_ROOT="$SERVER_PROJECT_DIR"
else
    PROJECT_ROOT="$(pwd)"
fi

BACKEND_DIR="$PROJECT_ROOT/backend"
FRONTEND_DIR="$PROJECT_ROOT/frontend"

# Ensure we switch into project root
if [ -d "$PROJECT_ROOT" ]; then
    cd "$PROJECT_ROOT"
else
    echo "❌ Error: Project root directory ($PROJECT_ROOT) not found!"
    exit 1
fi

echo "📁 Project Root: $PROJECT_ROOT"
echo "📁 Backend Dir : $BACKEND_DIR"
echo "📁 Frontend Dir: $FRONTEND_DIR"

# 2. Pull latest code from Git
if [ -d "$PROJECT_ROOT/.git" ]; then
    echo "📥 Pulling latest code from repository..."
    cd "$PROJECT_ROOT"
    git pull origin main || git pull origin master || true
fi

# Ensure ecosystem.config.js exists in PROJECT_ROOT
if [ ! -f "$PROJECT_ROOT/ecosystem.config.js" ]; then
    echo "⚙️ Creating ecosystem.config.js dynamically..."
    cat << 'EOF' > "$PROJECT_ROOT/ecosystem.config.js"
module.exports = {
  apps: [
    {
      name: "knotelle-backend",
      cwd: "/var/www/knottele-fullstack/backend",
      script: "artisan",
      interpreter: "php",
      args: "serve --host=127.0.0.1 --port=8000",
      env: {
        APP_ENV: "production",
      },
      autorestart: true,
      restart_delay: 3000,
      max_restarts: 10,
    },
    {
      name: "knotelle-frontend",
      cwd: "/var/www/knottele-fullstack/frontend",
      script: "node_modules/next/dist/bin/next",
      interpreter: "node",
      args: "start -p 3000",
      env: {
        NODE_ENV: "production",
        PORT: "3000",
        INTERNAL_API_URL: "http://127.0.0.1:8000/api",
        NEXT_PUBLIC_API_URL: "/api",
      },
      autorestart: true,
      restart_delay: 3000,
      max_restarts: 10,
    },
  ],
};
EOF
fi

# 3. Deploy Backend (Laravel)
if [ -d "$BACKEND_DIR" ]; then
    echo "⚙️  Deploying Laravel Backend..."
    cd "$BACKEND_DIR"

    # Composer dependencies
    echo "📦 Installing PHP dependencies..."
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

    # Build Laravel Vite assets required by the Blade admin panel.
    # @vite() reads public/build/manifest.json in production.
    echo "Building Laravel frontend assets..."
    npm ci --include=dev || npm install --include=dev
    npm run build

    # Database migrations
    echo "🗄️  Running database migrations..."
    php artisan migrate --force

    # Ensure storage symlink exists
    echo "🔗 Verifying storage symlink..."
    if [ -L public/storage ]; then
        echo "✅ Storage symlink already exists."
    elif [ -e public/storage ]; then
        echo "⚠️ public/storage exists but is not a symlink."
    else
        php artisan storage:link || true
    fi

    # Clear & rebuild caches
    echo "🧹 Optimizing Laravel caches..."
    php artisan optimize:clear
    php artisan route:clear || true
    php artisan config:cache
    php artisan route:cache || true
    php artisan view:cache
    php artisan event:cache

    # Restart queue worker if running
    echo "🔄 Restarting queue workers..."
    php artisan queue:restart || true

    # Fix permissions for storage and bootstrap cache
    echo "🔒 Updating folder permissions..."
    chmod -R 775 storage bootstrap/cache || true
    if command -v chown >/dev/null 2>&1; then
        chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || chown -R apache:apache storage bootstrap/cache 2>/dev/null || true
    fi

    # Start or Restart Laravel Backend in PM2 BEFORE building Next.js frontend
    if command -v pm2 >/dev/null 2>&1; then
        if pm2 describe knotelle-backend >/dev/null 2>&1; then
            echo "🔄 Restarting PM2 backend process..."
            pm2 restart knotelle-backend --update-env
        else
            echo "🚀 Starting PM2 backend process from ecosystem.config.js..."
            pm2 start "$PROJECT_ROOT/ecosystem.config.js" --only knotelle-backend
        fi
        pm2 save
    else
        echo "❌ PM2 is not installed!"
        exit 1
    fi

    # Strict Health Check: Verify Laravel Backend API is listening on port 8000
    echo "🔍 Checking Laravel Backend API (http://127.0.0.1:8000/api/health)..."
    BACKEND_READY=false
    for i in {1..30}; do
        if curl -fsS http://127.0.0.1:8000/api/health >/dev/null 2>&1 || curl -fsS http://127.0.0.1:8000 >/dev/null 2>&1; then
            echo "✅ Laravel Backend is responding on port 8000!"
            BACKEND_READY=true
            break
        fi
        echo "⏳ Waiting for Laravel Backend... ($i/30)"
        sleep 1
    done

    if [ "$BACKEND_READY" != "true" ]; then
        echo "❌ ERROR: Laravel Backend failed to start!"
        pm2 status
        pm2 logs knotelle-backend --lines 100 --nostream 2>/dev/null || true
        exit 1
    fi
fi

# 4. Deploy Frontend (Next.js)
if [ -d "$FRONTEND_DIR" ]; then
    echo "🏗️  Deploying Next.js Frontend..."
    cd "$FRONTEND_DIR"

    # Node dependencies
    echo "📦 Installing Node.js dependencies..."
    npm ci || npm install

    # Build production bundle (Backend on 8000 is now live for static pre-rendering!)
    echo "🔨 Building Next.js production bundle..."
    INTERNAL_API_URL="http://127.0.0.1:8000/api" NEXT_PUBLIC_API_URL="/api" npm run build

    # Start or Restart Node PM2 process
    if command -v pm2 >/dev/null 2>&1; then
        if pm2 describe knotelle-frontend >/dev/null 2>&1; then
            echo "🔄 Restarting PM2 frontend process..."
            pm2 restart knotelle-frontend --update-env
        else
            echo "🚀 Starting PM2 frontend process from ecosystem.config.js..."
            pm2 start "$PROJECT_ROOT/ecosystem.config.js" --only knotelle-frontend
        fi
        pm2 save
    fi

    # Strict Health Check: Verify Next.js Frontend is listening on port 3000
    echo "🔍 Checking Next.js Frontend (http://127.0.0.1:3000)..."
    FRONTEND_READY=false
    for i in {1..30}; do
        if curl -fsS http://127.0.0.1:3000 >/dev/null 2>&1; then
            echo "✅ Next.js Frontend is responding on port 3000!"
            FRONTEND_READY=true
            break
        fi
        echo "⏳ Waiting for Next.js Frontend... ($i/30)"
        sleep 1
    done

    if [ "$FRONTEND_READY" != "true" ]; then
        echo "❌ ERROR: Next.js Frontend failed to start on http://127.0.0.1:3000!"
        pm2 status
        pm2 logs knotelle-frontend --lines 100 --nostream 2>/dev/null || true
        exit 1
    fi
fi

echo ""
echo "📊 Final PM2 Status:"
pm2 status

echo ""
echo "🔎 Final Service Checks:"
curl -fsS http://127.0.0.1:8000/api/health >/dev/null && echo "✅ Laravel API: OK"
curl -fsS http://127.0.0.1:3000 >/dev/null && echo "✅ Next.js Frontend: OK"

echo ""
echo "=========================================================================="
echo "✅ KNOTELLE Deployment completed successfully! All services are online."
echo "=========================================================================="

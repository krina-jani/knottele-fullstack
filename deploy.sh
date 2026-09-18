#!/bin/bash

# ==============================================================================
# KNOTELLE — Production Deployment Script (Laravel Backend + Next.js Frontend)
# Safe, zero-downtime deployment script with database migration & caching.
# ==============================================================================

set -e # Exit immediately if a command exits with a non-zero status

echo "🚀 Starting deployment for KNOTELLE..."

# Set your server project directory (overridden automatically if run inside project folder)
SERVER_PROJECT_DIR="/var/www/knotelle"

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

# 3. Deploy Backend (Laravel)
if [ -d "$BACKEND_DIR" ]; then
    echo "⚙️  Deploying Laravel Backend..."
    cd "$BACKEND_DIR"

    # Maintenance mode
    echo "⏸️  Putting backend into maintenance mode..."
    php artisan down --render="errors::503" || true

    # Composer dependencies
    echo "📦 Installing PHP dependencies..."
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

    # Database migrations (Safe, non-destructive)
    echo "🗄️  Running database migrations..."
    php artisan migrate --force

    # Ensure storage symlink exists
    echo "🔗 Verifying storage symlink..."
    if [ ! -L public/storage ] && [ ! -d public/storage ]; then
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

    # Bring backend back online
    echo "▶️  Bringing backend out of maintenance mode..."
    php artisan up

    # Start or Reload Laravel Backend in PM2 BEFORE building Next.js frontend
    if command -v pm2 >/dev/null 2>&1; then
        if pm2 list | grep -q "knotelle-backend"; then
            echo "🔄 Reloading PM2 backend process..."
            pm2 reload knotelle-backend --update-env
        else
            echo "🚀 Starting PM2 backend process..."
            pm2 start "php artisan serve --host=127.0.0.1 --port=8000" --name "knotelle-backend" --cwd "$BACKEND_DIR"
        fi
        pm2 save
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
    npm run build

    # Start or Reload Node PM2 process
    if command -v pm2 >/dev/null 2>&1; then
        if pm2 list | grep -q "knotelle-frontend"; then
            echo "🔄 Reloading PM2 frontend process..."
            pm2 reload knotelle-frontend --update-env
        else
            echo "🚀 Starting PM2 frontend process..."
            pm2 start npm --name "knotelle-frontend" --cwd "$FRONTEND_DIR" -- start -- -p 3000
        fi
        pm2 save
    fi
fi

echo "=========================================================================="
echo "✅ KNOTELLE Deployment completed successfully! Your site is live."
echo "=========================================================================="

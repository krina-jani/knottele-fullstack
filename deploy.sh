#!/bin/bash
set -e

echo "🚀 Starting KNOTELLE Deployment..."

# 1. Move to project root
cd "$(dirname "$0")"
PROJECT_ROOT="$(pwd)"

# 2. Put application into maintenance mode if backend exists
if [ -d "$PROJECT_ROOT/backend" ]; then
    cd "$PROJECT_ROOT/backend"
    php artisan down || true
fi

# 3. Pull latest code from Git
echo "📥 Pulling latest code..."
cd "$PROJECT_ROOT"
git pull origin main || true

# 4. Install Composer dependencies
if [ -d "$PROJECT_ROOT/backend" ]; then
    echo "📦 Installing Composer dependencies..."
    cd "$PROJECT_ROOT/backend"
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# 5. Build Next.js customer frontend and sync to Laravel public
if [ -d "$PROJECT_ROOT/frontend" ]; then
    echo "🛍️ Building Frontend (Next.js -> Laravel public)..."
    cd "$PROJECT_ROOT/frontend"
    npm install
    npm run build:laravel
fi

# 6. Build Blade Admin Vite assets
if [ -d "$PROJECT_ROOT/backend" ]; then
    cd "$PROJECT_ROOT/backend"
    if [ -f "package.json" ]; then
        echo "⚙️ Building Admin assets..."
        npm install
        npm run build
    fi

    # Ensure static public/admin directory never shadows Laravel admin routes
    rm -rf public/admin

    # 7. Database Migrations
    echo "🗄️ Running Migrations..."
    php artisan migrate --force

    # 8. Storage Symlink
    echo "🔗 Linking Storage..."
    php artisan storage:link || true

    # 9. Clear and rebuild Laravel caches
    echo "⚡ Optimizing Laravel Caches..."
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache

    # 10. Restart queue workers
    echo "🔄 Restarting Queue..."
    php artisan queue:restart || true

    # 11. Folder permissions
    echo "🔒 Updating permissions..."
    chmod -R 775 storage bootstrap/cache || true
    chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || chown -R apache:apache storage bootstrap/cache 2>/dev/null || chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

    # 12. Bring application out of maintenance mode
    php artisan up
fi

# 13. Reload PM2 & Nginx
if command -v pm2 >/dev/null 2>&1; then
    echo "🔄 Reloading PM2 processes..."
    pm2 reload all --update-env 2>/dev/null || pm2 restart all 2>/dev/null || true
fi

if command -v systemctl >/dev/null 2>&1; then
    echo "🔄 Reloading Nginx..."
    systemctl reload nginx 2>/dev/null || true
fi

echo "=========================================="
echo "✅ KNOTELLE Deployment Completed Successfully!"
echo "=========================================="

#!/bin/bash
set -e

echo "🚀 Starting KNOTELLE Deployment..."

# 1. Move to backend directory
cd "$(dirname "$0")/backend"

# 2. Put application into maintenance mode
php artisan down || true

# 3. Pull latest code from Git
echo "📥 Pulling latest code..."
git pull origin main || true

# 4. Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 5. Build Next.js customer frontend and sync to Laravel public
if [ -d "../frontend" ]; then
    echo "🛍️ Building Frontend (Next.js -> Laravel public)..."
    cd ../frontend
    npm install
    npm run build:laravel
    cd ../backend
fi

# 6. Build Blade Admin Vite assets
if [ -f "package.json" ]; then
    echo "⚙️ Building Admin assets..."
    npm install
    npm run build
fi

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
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# 12. Bring application out of maintenance mode
php artisan up

echo "=========================================="
echo "✅ KNOTELLE Deployment Completed Successfully!"
echo "=========================================="

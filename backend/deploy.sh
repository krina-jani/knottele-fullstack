#!/bin/bash

# ==============================================================================
# Nuts & Nutrition - Production Deployment Script
# Run this script on the server as root to deploy the latest changes from GitHub
# ==============================================================================

# Ensure we are in the correct directory
cd /var/www/nutsandnutrition.shop/laravel-react-app || exit

echo "🚀 Starting deployment for Nuts & Nutrition..."

# 1. Put application into maintenance mode
echo "⏸️  Putting application into maintenance mode..."
php artisan down --render="errors::503" || true

# 2. Pull the latest changes from the main branch
echo "📥 Pulling latest code from GitHub..."
git pull origin main

# 3. Install/Update PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 4. Install/Update Node.js dependencies
echo "📦 Installing Node dependencies..."
npm ci

# 5. Build React frontend for production
echo "🏗️  Building React frontend..."
npm run build

# 6. Run database migrations (forces it without prompt in production)
echo "🗄️  Running database migrations..."
php artisan migrate --force

# 7. Clear old caches and optimize
echo "🧹 Clearing and rebuilding cache..."
php artisan optimize:clear
php artisan optimize
php artisan event:cache
php artisan view:cache

# 8. Restart queue workers (uncomment if you add jobs later)
# echo "🔄 Restarting queue workers..."
# php artisan queue:restart

# 9. Fix folder permissions (Critical since you are running this as root)
echo "🔒 Fixing file and folder permissions..."
chown -R apache:apache /var/www/nutsandnutrition.shop/laravel-react-app
chmod -R 775 /var/www/nutsandnutrition.shop/laravel-react-app/storage
chmod -R 775 /var/www/nutsandnutrition.shop/laravel-react-app/bootstrap/cache

# 10. Bring application out of maintenance mode
echo "▶️  Bringing application back online..."
php artisan up

echo "✅ Deployment completed successfully! Your site is now live with the latest changes."

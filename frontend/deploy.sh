#!/bin/bash
set -e

echo "==========================================="
echo "🧶 Deploying KNOTELLE Luxury Boutique..."
echo "==========================================="

cd /var/www/knotelle

# 1. Pull latest code from GitHub
echo "📥 1/3 Pulling latest code from GitHub..."
git pull origin main

# 2. Install dependencies if package.json changed (optional fast check)
# npm install --production=false

# 3. Build static export
echo "🔨 2/3 Building static HTML/CSS/JS export..."
npm run build

# 4. Test & Reload Nginx
echo "🔄 3/3 Testing & Reloading Nginx..."
nginx -t
systemctl reload nginx

echo "==========================================="
echo "✨ KNOTELLE Deployment Complete!"
echo "🌐 Live at: https://emperormediasolutions.com/knotelle/"
echo "==========================================="

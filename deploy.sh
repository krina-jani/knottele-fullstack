#!/bin/bash

# ==============================================================================
# KNOTELLE — Production Deployment Script (Unified Laravel Single-Server)
# ==============================================================================
# Architecture:
# - Frontend: Next.js statically compiled & synced to backend/public/
# - Admin:    Laravel Blade + Vite compiled to backend/public/build/
# - Server:   Native Laravel via Nginx + PHP-FPM (Zero background Node.js/PM2 needed)
# ==============================================================================

set -e # Exit immediately on any error
trap 'echo -e "\n\033[0;31m❌ Deployment failed on line $LINENO! Check logs above.\033[0m"' ERR

# ANSI Colors
CLR_RESET="\033[0m"
CLR_CYAN="\033[1;36m"
CLR_GREEN="\033[1;32m"
CLR_YELLOW="\033[1;33m"
CLR_MAGENTA="\033[1;35m"
CLR_RED="\033[1;31m"

echo -e "${CLR_CYAN}======================================================================${CLR_RESET}"
echo -e "${CLR_MAGENTA}  🚀 Starting Deployment for KNOTELLE (Unified Laravel Project)  ${CLR_RESET}"
echo -e "${CLR_CYAN}======================================================================${CLR_RESET}"

# 1. Determine Project Root Directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

if [ -d "$SCRIPT_DIR/backend" ] && [ -d "$SCRIPT_DIR/frontend" ]; then
    PROJECT_ROOT="$SCRIPT_DIR"
elif [ -d "$SCRIPT_DIR/app" ] && [ -f "$SCRIPT_DIR/artisan" ]; then
    PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
elif [ -d "/var/www/knottele-fullstack/backend" ]; then
    PROJECT_ROOT="/var/www/knottele-fullstack"
elif [ -d "/var/www/knottele/backend" ]; then
    PROJECT_ROOT="/var/www/knottele"
else
    PROJECT_ROOT="$(pwd)"
fi

BACKEND_DIR="$PROJECT_ROOT/backend"
FRONTEND_DIR="$PROJECT_ROOT/frontend"

cd "$PROJECT_ROOT"

echo -e "📁 Project Root : ${CLR_YELLOW}$PROJECT_ROOT${CLR_RESET}"
echo -e "📁 Backend Dir  : ${CLR_YELLOW}$BACKEND_DIR${CLR_RESET}"
echo -e "📁 Frontend Dir : ${CLR_YELLOW}$FRONTEND_DIR${CLR_RESET}"
echo ""

# 2. Pull Latest Code from Git
if [ -d "$PROJECT_ROOT/.git" ]; then
    echo -e "${CLR_CYAN}📥 [1/8] Pulling latest code from repository...${CLR_RESET}"
    git pull origin main || git pull origin master || true
else
    echo -e "${CLR_YELLOW}⚠️  No .git directory found. Skipping git pull.${CLR_RESET}"
fi

# 3. Install Backend Composer Dependencies
if [ -d "$BACKEND_DIR" ]; then
    echo -e "${CLR_CYAN}📦 [2/8] Installing PHP production dependencies...${CLR_RESET}"
    cd "$BACKEND_DIR"
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
fi

# 4. Compile Customer Frontend & Sync to Laravel Public
if [ -d "$FRONTEND_DIR" ] && [ -f "$FRONTEND_DIR/package.json" ]; then
    echo -e "${CLR_CYAN}🛍️  [3/8] Building Next.js Frontend & syncing to Laravel public...${CLR_RESET}"
    cd "$FRONTEND_DIR"
    npm ci --include=dev || npm install
    npm run build:laravel
    echo -e "${CLR_GREEN}   ✓ Next.js customer site exported to backend/public/${CLR_RESET}"
else
    echo -e "${CLR_YELLOW}⚠️  Frontend directory not found or missing package.json.${CLR_RESET}"
fi

# 5. Compile Laravel Vite Assets (Admin Blade Dashboard)
if [ -d "$BACKEND_DIR" ] && [ -f "$BACKEND_DIR/package.json" ]; then
    echo -e "${CLR_CYAN}⚙️  [4/8] Building Laravel Vite Admin assets...${CLR_RESET}"
    cd "$BACKEND_DIR"
    npm ci --include=dev || npm install
    npm run build
    echo -e "${CLR_GREEN}   ✓ Blade Admin assets built to backend/public/build/${CLR_RESET}"
fi

# 6. Execute Native Laravel Deployment (Artisan Engine)
if [ -d "$BACKEND_DIR" ]; then
    echo -e "${CLR_CYAN}⚡ [5/8] Running Native Laravel Deployment Engine...${CLR_RESET}"
    cd "$BACKEND_DIR"
    php artisan app:deploy --skip-frontend --skip-admin
fi

# 7. Linux Permissions & Storage Symlink
if [ -d "$BACKEND_DIR" ]; then
    echo -e "${CLR_CYAN}🔒 [6/8] Setting secure directory permissions...${CLR_RESET}"
    cd "$BACKEND_DIR"

    chmod -R 775 storage bootstrap/cache 2>/dev/null || true

    # Automatically detect web server user (www-data, nginx, or apache)
    WEB_USER="www-data"
    if id "nginx" &>/dev/null; then
        WEB_USER="nginx"
    elif id "apache" &>/dev/null; then
        WEB_USER="apache"
    fi

    if command -v chown >/dev/null 2>&1; then
        chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache 2>/dev/null || true
        echo -e "${CLR_GREEN}   ✓ Permissions granted to $WEB_USER${CLR_RESET}"
    fi
fi

# 8. Reload PHP-FPM / Web Server (Clear OPcache)
echo -e "${CLR_CYAN}🔄 [7/8] Reloading PHP-FPM to clear opcode caches...${CLR_RESET}"
if command -v systemctl >/dev/null 2>&1; then
    for FPM_SERVICE in php8.3-fpm php8.2-fpm php8.4-fpm php-fpm; do
        if systemctl is-active --quiet "$FPM_SERVICE" 2>/dev/null; then
            systemctl reload "$FPM_SERVICE"
            echo -e "${CLR_GREEN}   ✓ Reloaded $FPM_SERVICE${CLR_RESET}"
            break
        fi
    done

    if systemctl is-active --quiet nginx 2>/dev/null; then
        systemctl reload nginx
        echo -e "${CLR_GREEN}   ✓ Reloaded Nginx${CLR_RESET}"
    fi
else
    echo -e "${CLR_YELLOW}   systemctl not available, skipping service reload.${CLR_RESET}"
fi

# 9. Health Check Verification
echo -e "${CLR_CYAN}🩺 [8/8] Verifying Production Health...${CLR_RESET}"
if [ -f "$BACKEND_DIR/public/index.html" ]; then
    echo -e "${CLR_GREEN}   ✓ Customer Storefront (index.html): Present${CLR_RESET}"
else
    echo -e "${CLR_RED}   ✗ WARNING: public/index.html is missing!${CLR_RESET}"
fi

if [ -L "$BACKEND_DIR/public/storage" ] || [ -d "$BACKEND_DIR/public/storage" ]; then
    echo -e "${CLR_GREEN}   ✓ Public Storage Symlink: Active${CLR_RESET}"
else
    echo -e "${CLR_YELLOW}   ⚠️ Storage symlink might need attention.${CLR_RESET}"
fi

# Optional HTTP Health Check if curl is available
if command -v curl >/dev/null 2>&1; then
    APP_URL_CONFIG=$(grep -E "^APP_URL=" "$BACKEND_DIR/.env" 2>/dev/null | cut -d '=' -f2 | tr -d '"' | tr -d "'" || echo "")
    CHECK_URL="${APP_URL_CONFIG:-http://127.0.0.1}/up"
    echo -e "   Checking $CHECK_URL..."
    if curl -fsS "$CHECK_URL" >/dev/null 2>&1; then
        echo -e "${CLR_GREEN}   ✓ Live Health Endpoint (/up): HTTP 200 OK${CLR_RESET}"
    else
        echo -e "${CLR_YELLOW}   ⚠️ Could not reach $CHECK_URL (Server might be on a different hostname/port).${CLR_RESET}"
    fi
fi

echo ""
echo -e "${CLR_GREEN}======================================================================${CLR_RESET}"
echo -e "${CLR_GREEN}  🎉 KNOTELLE DEPLOYMENT FINISHED SUCCESSFULLY!                      ${CLR_RESET}"
echo -e "${CLR_GREEN}======================================================================${CLR_RESET}"
echo -e "  🛍️ Customer Site : Unified via Laravel public/"
echo -e "  🔐 Admin Panel   : /admin/login"
echo -e "  ⚡ REST API      : /api/*"
echo -e "  🩺 Health Status : /up"
echo ""

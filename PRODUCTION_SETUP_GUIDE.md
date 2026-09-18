# 🌐 KNOTELLE — Production Deployment & Go-Live Guide

This document provides complete, step-by-step instructions for taking **KNOTELLE** live on a production Linux server (Ubuntu 22.04 / 24.04 LTS VPS, AWS EC2, DigitalOcean Droplet, Hostinger VPS, Hetzner, etc.).

---

## 🏛️ 1. Architecture Overview (Why Node/PM2 is NOT needed in Production)

Your project uses a **Unified Single-Server Architecture**:
```
┌─────────────────────────────────────────────────────────────┐
│                      LIVE SERVER (Port 80 / 443)            │
│                       NGINX + PHP-FPM                       │
└──────────────────────────────┬──────────────────────────────┘
                               │
                Points root to /backend/public
                               │
     ┌─────────────────────────┼─────────────────────────┐
     ▼                         ▼                         ▼
┌──────────────┐       ┌──────────────┐        ┌──────────────────┐
│ Next.js      │       │ Laravel      │        │ REST APIs        │
│ Customer     │       │ Blade Admin  │        │                  │
│ Storefront   │       │ Dashboard    │        │                  │
│ (/, /shop,   │       │ (/admin/*)   │        │ (/api/*)         │
│ /checkout)   │       │              │        │                  │
└──────────────┘       └──────────────┘        └──────────────────┘
```

- **Build Time**: `npm run build:laravel` runs Next.js export and synchronizes all static HTML, CSS, JS chunks, and images directly into `backend/public/`.
- **Runtime**: Laravel routes (`backend/routes/web.php`) and Nginx serve everything directly from `backend/public/`.
- **Result**: You **do not need** PM2 or a background Node.js process running `next start` on port 3000. Everything is served at maximum speed by Nginx and PHP-FPM!

---

## 📋 2. Server Prerequisites & One-Command Installation

Log in to your Ubuntu production server via SSH:
```bash
ssh root@your-server-ip
```

### Install PHP 8.2+, Nginx, MySQL, Node.js, Composer, and Supervisor:
Run this all-in-one command block:

```bash
# 1. Update system packages
sudo apt update && sudo apt upgrade -y

# 2. Add PHP repository and install PHP 8.2 + extensions
sudo apt install -y software-properties-common curl git unzip
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd \
    php8.2-intl php8.2-cli

# 3. Install Nginx & MySQL Server
sudo apt install -y nginx mysql-server supervisor certbot python3-certbot-nginx

# 4. Install Composer (PHP Dependency Manager)
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# 5. Install Node.js 20 (LTS) & NPM (Used for building frontend assets)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

---

## 🗄️ 3. Database Setup (MySQL)

Access the MySQL shell:
```bash
sudo mysql
```

Create database and user:
```sql
CREATE DATABASE knottele CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'knottele_user'@'localhost' IDENTIFIED BY 'YourStrongPassword123!';
GRANT ALL PRIVILEGES ON knottele.* TO 'knottele_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 📥 4. Clone Project & Configure `.env`

### Clone your Git repository to `/var/www/knottele`:
```bash
sudo mkdir -p /var/www/knottele
sudo chown -R $USER:$USER /var/www/knottele
git clone https://github.com/krina-jani/knottele-fullstack.git /var/www/knottele
cd /var/www/knottele
```

### Create and configure production `backend/.env`:
```bash
cd /var/www/knottele/backend
cp .env.example .env
nano .env
```

**Set these production values in `.env`:**
```ini
APP_NAME=KNOTELLE
APP_ENV=production
APP_KEY=                          # Generated in next step
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=knottele
DB_USERNAME=knottele_user
DB_PASSWORD=YourStrongPassword123!

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=nexora@vardaansmartsolutions.com
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=nexora@vardaansmartsolutions.com
MAIL_FROM_NAME="KNOTELLE"

RAZORPAY_KEY_ID=rzp_live_your_live_key
RAZORPAY_KEY_SECRET=your_live_secret
```

Generate the application encryption key:
```bash
php artisan key:generate
```

---

## 🚀 5. Deploying the Application

You have **3 Native Ways** to deploy your project:

### Option A: Laravel Native Artisan Command (Recommended)
From the `backend` folder:
```bash
cd /var/www/knottele/backend
php artisan deploy
```
*Options available:*
- `php artisan deploy` — Runs complete deployment (Frontend sync + Admin build + Migrations + Cache + Symlink + Health check).
- `php artisan deploy --quick` — Rapid deploy skipping asset compilation (for backend-only changes).
- `php artisan deploy --skip-frontend` — Rebuilds only admin and runs backend migrations/cache.

---

### Option B: The Production Shell Script (`deploy.sh`)
From the project root:
```bash
cd /var/www/knottele
chmod +x deploy.sh
./deploy.sh
```
*This handles Git pull, Composer install, NPM builds, Artisan deployment, folder permissions (`chmod`/`chown`), PHP-FPM reload, and health verification in one automated command.*

---

### Option C: Laravel Envoy (Blade Language Deployment)
If you have Laravel Envoy installed:
```bash
composer global require laravel/envoy
cd /var/www/knottele
envoy run deploy
```

---

## 🌐 6. Nginx Virtual Host Configuration

Create an Nginx configuration file:
```bash
sudo nano /etc/nginx/sites-available/knottele.conf
```

Paste the following production configuration (replace `yourdomain.com` with your actual domain):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;

    # Point directly to Laravel's public directory
    root /var/www/knottele/backend/public;
    index index.php index.html;

    charset utf-8;
    client_max_body_size 64M;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml application/json application/javascript application/rss+xml application/atom+xml image/svg+xml;

    # Next.js Static Chunks & Build Assets (Aggressive Caching)
    location /_next/static/ {
        alias /var/www/knottele/backend/public/_next/static/;
        expires 365d;
        access_log off;
        add_header Cache-Control "public, max-age=31536000, immutable";
    }

    # Public Uploaded Storage & Media
    location /storage/ {
        alias /var/www/knottele/backend/public/storage/;
        expires 30d;
        access_log off;
        add_header Cache-Control "public, max-age=2592000";
    }

    # Static Assets (Images, Icons, CSS, JS)
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|webp|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        access_log off;
        add_header Cache-Control "public, no-transform";
        try_files $uri $uri/ =404;
    }

    # Main Router: Directs to Laravel index.php (Handling customer SPA & Admin routes)
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # Laravel PHP-FPM Handler
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
    }

    # Deny access to hidden files (.env, .git, etc.)
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site and reload Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/knottele.conf /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🔒 7. Free SSL Certificate (HTTPS) with Let's Encrypt

Once your domain's DNS `A` records point to your server IP, obtain a free SSL certificate:

```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

Select the option to automatically redirect HTTP traffic to HTTPS.
Certbot automatically installs a renewal cron job. You can verify renewal with:
```bash
sudo certbot renew --dry-run
```

---

## ⚙️ 8. Background Queue Worker (Supervisor)

Since `QUEUE_CONNECTION=database`, you need a persistent daemon to process order emails, invoices, and notifications in the background.

Create a Supervisor worker configuration:
```bash
sudo nano /etc/supervisor/conf.d/knottele-worker.conf
```

Paste:
```ini
[program:knottele-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/knottele/backend/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/knottele/backend/storage/logs/worker.log
stopwaitsecs=3600
```

Start Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start knottele-worker:*
```

Check worker status:
```bash
sudo supervisorctl status
```

---

## ⏰ 9. Laravel Cron Scheduler

Enable automatic database backups, cart expirations, and scheduled tasks.

Open the crontab for `www-data`:
```bash
sudo crontab -u www-data -e
```

Add this single line at the end:
```cron
* * * * * cd /var/www/knottele/backend && php artisan schedule:run >> /dev/null 2>&1
```

---

## ✅ 10. Go-Live Verification Checklist

Once your site is live, perform these verification checks:

| Service | URL | Check Criteria |
| :--- | :--- | :--- |
| 🛍️ **Customer Storefront** | `https://yourdomain.com/` | Homepage loads with hero slider and products |
| 📦 **Shop Catalog** | `https://yourdomain.com/shop` | Filtering & categories work |
| 🎨 **Custom Order Builder** | `https://yourdomain.com/custom-order` | Multi-step crochet customizer opens |
| 💌 **Contact Page** | `https://yourdomain.com/contact` | Submitting form creates record |
| 🔐 **Admin Dashboard** | `https://yourdomain.com/admin/login` | Login with `admin@example.com` / `Admin@123` |
| ⚡ **Customer REST API** | `https://yourdomain.com/api/customer/products` | Returns JSON data with 200 OK |
| 🩺 **System Health** | `https://yourdomain.com/up` | Returns 200 OK |

---

## 🔄 11. Daily Maintenance & Updating Code

Whenever you make future code changes and push to GitHub:
```bash
cd /var/www/knottele
./deploy.sh
```
Or directly using Laravel:
```bash
cd /var/www/knottele/backend
git pull origin main
php artisan deploy
```

Everything else is automatically compiled, optimized, and served seamlessly!

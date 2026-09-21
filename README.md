# KNOTELLE — Full-Stack E-Commerce (Unified Single-Server)

Handcrafted Crochet Boutique full-stack application.
The Next.js 16 frontend is unified with the Laravel 12 backend to serve the customer website, admin panel, and REST APIs from a single server host.

---

## Repository Structure

```
knottele-adminpanel/
├── frontend/             # Next.js 16 App Router (Tailwind CSS, React 19)
├── backend/              # Laravel 12 Backend API & Blade Admin Panel
│   └── public/           # Unified static export & Laravel public assets
├── .gitignore            # Clean root gitignore
└── README.md             # Documentation
```

---

## Quick Start & Running Locally

### 1. Start the Unified Server (Laravel)
```bash
cd backend
php artisan serve
```

### 2. Local Service URLs (Port 8000)
- **Customer Storefront**: [http://localhost:8000/](http://localhost:8000/)
- **Shop Catalog**: [http://localhost:8000/shop](http://localhost:8000/shop)
- **Custom Order Builder**: [http://localhost:8000/custom-order](http://localhost:8000/custom-order)
- **Contact Studio**: [http://localhost:8000/contact](http://localhost:8000/contact)
- **Admin Sign In**: [http://localhost:8000/admin/signin](http://localhost:8000/admin/signin)
  - **Email**: `admin@example.com`
  - **Password**: `Admin@123`
- **Admin Dashboard**: [http://localhost:8000/admin/dashboard](http://localhost:8000/admin/dashboard)
- **Customer REST APIs**: [http://localhost:8000/api/customer/products](http://localhost:8000/api/customer/products)

### 3. Frontend Development Mode (Optional)
If you are developing and want live hot-reloading for the Next.js UI:
- Backend: `cd backend && php artisan serve` ([http://localhost:8000](http://localhost:8000))
- Frontend Dev Server: `cd frontend && npm run dev` ([http://localhost:3000](http://localhost:3000))

### 4. Build & Sync Frontend to Laravel
Whenever you make changes to the Next.js frontend UI:
```bash
cd frontend
npm install
npm run build:laravel
```
*This compiles Next.js and copies all static pages, JS/CSS bundles, and media directly into `backend/public/`.*

---

## 🌐 Live Production URLs (VPS: 187.127.158.24)

### 👑 Super Admin Panel
- **Admin Dashboard**: **[http://187.127.158.24/knottele/admin/dashboard](http://187.127.158.24/knottele/admin/dashboard)**
- **Admin Sign In / Login**: **[http://187.127.158.24/knottele/admin/signin](http://187.127.158.24/knottele/admin/signin)**
- **Credentials**:
  - **Email**: `admin@example.com`
  - **Password**: `Admin@123` *(case-sensitive)*

> **Note**: Always use `/admin/signin` for admin authentication. This bypasses Nginx static directory collisions and directly connects to Laravel PHP 8.3. Once signed in, you are automatically redirected to the Dashboard.

### 🛍️ Customer Storefront
- **Storefront Homepage**: [http://187.127.158.24/knottele/](http://187.127.158.24/knottele/)
- **Shop All Products**: [http://187.127.158.24/knottele/shop](http://187.127.158.24/knottele/shop)
- **Customer Login**: [http://187.127.158.24/knottele/login](http://187.127.158.24/knottele/login)
  - Demo Customer: `user@example.com` / `password123`
- **Customer Registration**: [http://187.127.158.24/knottele/signup](http://187.127.158.24/knottele/signup)
- **Cart**: [http://187.127.158.24/knottele/cart](http://187.127.158.24/knottele/cart)

---

## 📋 Direct Live Links for All Admin Modules

| Admin Module | Direct Live URL |
| :--- | :--- |
| **Admin Dashboard** | **[http://187.127.158.24/knottele/admin/dashboard](http://187.127.158.24/knottele/admin/dashboard)** |
| **All Orders** | **[http://187.127.158.24/knottele/admin/orders](http://187.127.158.24/knottele/admin/orders)** |
| **All Products** | **[http://187.127.158.24/knottele/admin/products](http://187.127.158.24/knottele/admin/products)** |
| **Add New Product** | **[http://187.127.158.24/knottele/admin/products/create](http://187.127.158.24/knottele/admin/products/create)** |
| **Categories** | **[http://187.127.158.24/knottele/admin/categories](http://187.127.158.24/knottele/admin/categories)** |
| **Add Category** | **[http://187.127.158.24/knottele/admin/categories/create](http://187.127.158.24/knottele/admin/categories/create)** |
| **Brands** | **[http://187.127.158.24/knottele/admin/brands](http://187.127.158.24/knottele/admin/brands)** |
| **Customers / Users** | **[http://187.127.158.24/knottele/admin/users](http://187.127.158.24/knottele/admin/users)** |
| **Taxes** | **[http://187.127.158.24/knottele/admin/taxes](http://187.127.158.24/knottele/admin/taxes)** |
| **Offers & Discounts** | **[http://187.127.158.24/knottele/admin/offers](http://187.127.158.24/knottele/admin/offers)** |
| **Media Manager** | **[http://187.127.158.24/knottele/admin/media](http://187.127.158.24/knottele/admin/media)** |
| **CRM (Sliders / Banners)** | **[http://187.127.158.24/knottele/admin/crm/banners](http://187.127.158.24/knottele/admin/crm/banners)** |
| **CRM (Home Sections)** | **[http://187.127.158.24/knottele/admin/crm/home-sections](http://187.127.158.24/knottele/admin/crm/home-sections)** |
| **Settings** | **[http://187.127.158.24/knottele/admin/settings](http://187.127.158.24/knottele/admin/settings)** |
| **Shipping** | **[http://187.127.158.24/knottele/admin/shipping](http://187.127.158.24/knottele/admin/shipping)** |
| **Reviews** | **[http://187.127.158.24/knottele/admin/reviews](http://187.127.158.24/knottele/admin/reviews)** |
| **Contact Messages** | **[http://187.127.158.24/knottele/admin/contact-messages](http://187.127.158.24/knottele/admin/contact-messages)** |

---

## 🚀 Production Deployment on VPS

### Fast Update Command (Takes ~5 seconds):
In your SSH terminal on the VPS (`ssh root@187.127.158.24`):
```bash
cd /var/www/knottele-fullstack && git reset --hard HEAD && git pull origin main && rm -rf backend/public/admin backend/public/knottele/admin && cd backend && php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && cd .. && systemctl reload nginx
```

### Full Zero-Downtime Deployment:
```bash
cd /var/www/knottele-fullstack && chmod +x deploy.sh && ./deploy.sh
```

📖 For full Linux server setup, Nginx configuration, free SSL, queue workers, and domain setup, see [PRODUCTION_SETUP_GUIDE.md](PRODUCTION_SETUP_GUIDE.md).

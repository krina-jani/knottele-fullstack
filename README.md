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

## Quick Start & Running

### 1. Build & Export Frontend to Laravel
Whenever you make changes to the frontend, run:
```bash
cd frontend
npm install
npm run build:laravel
```
*This compiles Next.js and automatically copies all static pages, JS/CSS chunks, and assets into `backend/public/`.*

### 2. Start the Unified Server (Laravel)
```bash
cd backend
php artisan serve
```

### 3. Access All Services on One Port (8000)
- **Customer Storefront**: [http://localhost:8000/](http://localhost:8000/)
- **Shop Catalog**: [http://localhost:8000/shop](http://localhost:8000/shop)
- **Custom Order Builder**: [http://localhost:8000/custom-order](http://localhost:8000/custom-order)
- **Contact Studio**: [http://localhost:8000/contact](http://localhost:8000/contact)
- **Admin Login**: [http://localhost:8000/admin/login](http://localhost:8000/admin/login)
  - **Credentials**: `admin@example.com` / `Admin@123`
- **Customer REST APIs**: [http://localhost:8000/api/customer/products](http://localhost:8000/api/customer/products)

---

## Local Development Mode (Optional)
If you want live hot-reloading for the frontend during development:
- Run backend: `cd backend && php artisan serve` (`http://localhost:8000`)
- Run frontend dev server: `cd frontend && npm run dev` (`http://localhost:3000`)









Unified Single-Server Architecture Implemented & Deployed
The Next.js customer frontend website, the Laravel admin panel, and the REST APIs are now unified to run seamlessly from a single Laravel server host.

What Was Configured
Automated Export & Sync Script:

Added frontend/scripts/sync-to-backend.js and "build:laravel" script in frontend/package.json.
Running npm run build:laravel automatically compiles Next.js and copies all static pages (index.html, shop/index.html, contact/index.html, checkout/index.html, _next/ bundle chunks, and assets) directly into backend/public/.
Unified Routing in Laravel (backend/routes/web.php):

/: Serves the Knotelle Customer Homepage.
/{any}: Serves static sub-pages (/shop, /contact, /product/[slug], /category/[slug], /checkout, /custom-order, etc.) with client-side routing fallback.
/admin/*: Dedicated Laravel Blade Admin Dashboard and authentication.
/api/*: High-performance JSON REST APIs for customer & admin operations.
Same-Origin Relative APIs (frontend/src/lib/api.ts):

The frontend automatically calls relative /api/customer/... endpoints, eliminating cross-origin / CORS overhead, port configuration, or domain mismatches.
Repository Synced on GitHub:

All commits pushed to https://github.com/krina-jani/knottele-fullstack.git (main branch).
How to Run Everything on a Single Port
powershell
# 1. (Optional) Rebuild frontend after editing UI
cd K:\knottele-adminpanel\frontend
npm run build:laravel
# 2. Start the unified server
cd K:\knottele-adminpanel\backend
php artisan serve
Access URLs (All on Port 8000)
Customer Storefront: http://127.0.0.1:8000/
Shop Page: http://127.0.0.1:8000/shop/
Product Details: http://127.0.0.1:8000/product/cute-bunny-keychain/
Contact Studio: http://127.0.0.1:8000/contact/
Checkout: http://127.0.0.1:8000/checkout/
Admin Login: http://127.0.0.1:8000/admin/login (admin@example.com / Admin@123)
Customer REST APIs: http://127.0.0.1:8000/api/customer/categories
6:37 PM
give me command.
fo run this project.
6:39 PM
Here are the exact commands to run the project:

Step 1: Start the Unified Server (Laravel)
Open your terminal and run:

powershell
cd K:\knottele-adminpanel\backend
php artisan serve
Step 2: Open in Your Browser
Once php artisan serve is running, open:

Service	URL	Credentials
🛍️ Customer Website	http://127.0.0.1:8000/	—
📦 Shop Catalog	http://127.0.0.1:8000/shop	—
💌 Contact Studio	http://127.0.0.1:8000/contact	—
🔐 Admin Panel	http://127.0.0.1:8000/admin/login	Email: admin@example.com
Password: Admin@123
⚡ Backend API	http://127.0.0.1:8000/api/customer/products	—
💡 If You Ever Make Changes to the Frontend Code:
Run this command to re-build and automatically sync the frontend to Laravel:

powershell
cd K:\knottele-adminpanel\frontend
npm run build:laravel

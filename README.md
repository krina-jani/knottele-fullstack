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

# Food Ordering System

A Laravel-based Food Ordering System for a final project. It includes customer ordering, admin menu management, order approval/status tracking, reports, and REST API endpoints.

## Features

### Customer
- Register, login, and logout
- Browse available menu items
- Select item quantities
- Place food orders
- View and track order status
- Cancel orders while still pending/approved

### Admin
- Login as admin
- Dashboard statistics
- Manage menu items with CRUD
- View all orders
- Approve/reject orders
- Update order status: pending, approved, preparing, out for delivery, delivered, rejected, cancelled
- View reports
- Export auto-generated report as PDF/CSV
- Import menu items from CSV

### Rider
- Login as rider
- View delivery orders
- Mark orders as out for delivery
- Upload delivery proof image
- Mark orders as delivered

### API
- `GET /api/menu-items`
- `POST /api/menu-items`
- `GET /api/menu-items/{id}`
- `PUT/PATCH /api/menu-items/{id}`
- `DELETE /api/menu-items/{id}`
- `GET /api/orders`
- `POST /api/orders`
- `GET /api/orders/{id}`
- `PUT/PATCH /api/orders/{id}`
- `DELETE /api/orders/{id}`
- `GET /api/reports/orders`
- `GET /api/reports/export`

## Default Accounts

Admin:
- Email: `admin@example.com`
- Password: `password`

Customer:
- Email: `customer@example.com`
- Password: `password`

Rider:
- Email: `rider@example.com`
- Password: `password`

## Setup Instructions

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

## Important Test Commands

```bash
php artisan route:list
php artisan migrate:fresh --seed
npm run build
```

## Deployment Notes

For Render/Railway deployment, make sure your build process installs Composer and NPM dependencies, runs migrations/seeding if needed, and runs:

```bash
npm install && npm run build
php artisan optimize:clear
```

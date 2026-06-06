# Food Ordering System

## Submitted By

- Lizelle mangunay
- Jubelle kate pridas
- Jenny Flores

A Laravel-based Food Ordering System made for a final project. The system allows customers to browse menu items and place orders, admins to manage menu items and orders, and riders to handle deliveries with delivery proof upload.

## Features

### Customer
- Register and login
- Browse available menu items
- Place food orders
- View order history and order details
- Track order status
- Cancel orders when allowed

### Admin
- Login as admin
- View dashboard statistics
- Manage menu items with CRUD
- Import menu items using CSV
- Download sample CSV format
- View all orders
- Approve or reject orders
- Update order status
- View rider delivery proof
- View reports and sales trend
- Export reports as PDF or CSV

### Rider
- Login as rider
- View delivery orders
- Mark orders as out for delivery
- Upload delivery proof image
- Mark orders as delivered
- View delivered orders assigned to the rider

## Default Accounts

Admin:

    Email: admin@example.com
    Password: password

Customer:

    Email: customer@example.com
    Password: password

Rider:

    Email: rider@example.com
    Password: password

## Local Setup

For Windows PowerShell:

    composer install
    npm install
    copy .env.example .env
    php artisan key:generate
    php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
    php artisan migrate --seed
    npm run build
    php artisan serve

Open the project:

    http://127.0.0.1:8000

## API Token Login

Use `/api/login` first to get an API token.

Login request:

    POST http://127.0.0.1:8000/api/login

Render login request:

    POST https://food-ordering-system-0fko.onrender.com/api/login

Headers:

    Accept: application/json
    Content-Type: application/json

Body:

    {
      "email": "admin@example.com",
      "password": "password"
    }

Sample response:

    {
      "message": "Login successful",
      "token": "1|sampleTokenHere",
      "user": {
        "email": "admin@example.com",
        "role": "admin"
      }
    }

Use the token in protected API requests:

    Authorization: Bearer 1|sampleTokenHere
    Accept: application/json

Example protected request:

    GET /api/menu-items

Logout:

    POST /api/logout

## API Endpoints

Public:

    POST /api/login

Protected:

    GET /api/user
    POST /api/logout

    GET /api/menu-items
    POST /api/menu-items
    GET /api/menu-items/{id}
    PUT/PATCH /api/menu-items/{id}
    DELETE /api/menu-items/{id}

    GET /api/orders
    POST /api/orders
    GET /api/orders/{id}
    PUT/PATCH /api/orders/{id}
    DELETE /api/orders/{id}

    GET /api/reports/orders
    GET /api/reports/export

## CSV Import

Admin can import menu items here:

    Admin Dashboard → Reports → Import CSV

CSV columns:

    name,description,price,category,image_url,is_available

Only `name` and `price` are required.

Sample row:

    Burger,Cheesy beef burger,99,Meals,,1

## Delivery Proof

Rider uploads delivery proof here:

    Rider Dashboard → View Order → Upload Delivery Proof → Mark as Delivered

Admin can view delivery proof here:

    Admin Dashboard → Orders → View Order → Delivery Proof

## Main Web Routes

    /                         Home page
    /login                    Login page
    /register                 Register page
    /menu                     Public menu page
    /customer/menu            Customer menu page
    /customer/orders          Customer orders
    /rider/dashboard          Rider dashboard
    /admin/dashboard          Admin dashboard
    /admin/menu               Admin menu management
    /admin/orders             Admin order management
    /admin/reports            Admin reports
    /admin/reports/import-csv CSV import page

## Render Deployment

This project is deployed on Render using Docker.

Render environment variables:

    APP_ENV=production
    APP_DEBUG=false
    APP_URL=https://food-ordering-system-0fko.onrender.com
    CACHE_STORE=file
    DB_CONNECTION=sqlite
    DB_DATABASE=/var/www/html/database/database.sqlite
    LOG_CHANNEL=stderr
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=file

The Dockerfile prepares the needed folders:

    storage/
    bootstrap/cache/
    database/
    public/delivery_proofs/

The Dockerfile also runs:

    php artisan config:clear && php artisan storage:link || true && php artisan migrate --force --seed && apache2-foreground

## Important Commands

    php artisan optimize:clear
    php artisan route:list
    php artisan migrate:fresh --seed
    npm run build

## GitHub Notes

Do not commit these files or folders:

    .env
    vendor/
    node_modules/
    database/database.sqlite
    storage/framework/views/
    storage/framework/sessions/
    storage/logs/

Push changes:

    git add .
    git commit -m "Update food ordering system"
    git push origin main

If your branch is not main:

    git branch -M main

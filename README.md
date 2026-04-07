# Technical Event Management System
### PHP + MySQL | 3-Role Platform

---

## 🚀 Quick Setup

### 1. Database Setup
1. Open **phpMyAdmin** or  MySQL client
2. Run the SQL file: `database.sql`
3. This creates all tables and a default admin account

### 2. Configure Connection
Edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        //  MySQL username
define('DB_PASS', '');            //  MySQL password
define('DB_NAME', 'tech_event_db');
define('SITE_URL', 'http://localhost/technical_event_management');
```

### 3. Deploy Files
Place the project folder inside your web server root:
- **XAMPP**: `C:/xampp/htdocs/technical_event_management/`
- **WAMP**: `C:/wamp64/www/technical_event_management/`
- **Linux Apache**: `/var/www/html/technical_event_management/`

### 4. Open in Browser
```
http://localhost/technical_event_management/
```

---

## 🔐 Default Login Credentials

| Role  | Email                   | Password   |
|-------|-------------------------|------------|
| Admin | admin@techevents.com    | password   |

> **Note:** The default admin password is `password` (hashed with bcrypt). Change it after first login.

---

## 📁 Project Structure

```
technical_event_management/
├── index.php                  ← Home / Landing page
├── database.sql               ← Database schema + seed data
├── includes/
│   └── config.php             ← DB config, session, helpers
├── assets/
│   ├── css/style.css          ← Global stylesheet
│   └── uploads/               ← Product images (auto-created)
├── admin/
│   ├── login.php              ← Admin login
│   ├── signup.php             ← Add new admin
│   ├── dashboard.php          ← Admin dashboard with stats
│   ├── users.php              ← Manage users (block/unblock/delete)
│   ├── vendors.php            ← Manage vendors (approve/block)
│   ├── products.php           ← View all products
│   ├── orders.php             ← View all orders
│   ├── order_detail.php       ← Order detail + status update
│   ├── requests.php           ← View item requests
│   ├── logout.php
│   └── includes/sidebar.php
├── vendor/
│   ├── login.php              ← Vendor login
│   ├── signup.php             ← Vendor registration
│   ├── dashboard.php          ← Vendor dashboard
│   ├── products.php           ← Manage own products
│   ├── add_product.php        ← Add new product
│   ├── edit_product.php       ← Edit product
│   ├── orders.php             ← View & update order items
│   ├── requests.php           ← View item requests from users
│   ├── logout.php
│   └── includes/sidebar.php
└── user/
    ├── login.php              ← User login
    ├── signup.php             ← User registration
    ├── portal.php             ← Browse products (with search/filter)
    ├── cart.php               ← Shopping cart
    ├── checkout.php           ← Checkout (Cash / UPI)
    ├── success.php            ← Order success page
    ├── orders.php             ← Order history + tracking
    ├── request_item.php       ← Request unlisted items
    ├── logout.php
    └── includes/navbar.php
```

---

## 🎯 Features by Role

### 👤 User / Event Planner
- Register & Login
- Browse products with search & category filter
- Add to cart, update quantities, remove items
- Checkout with Cash or UPI
- View order history and real-time status
- Request items not listed in catalog

### 🏪 Vendor / Supplier
- Register (pending admin approval) & Login
- Dashboard with revenue & order stats
- Add/Edit/Delete products with image upload
- Toggle product active/inactive status
- View and update status of incoming orders
- View item requests from users

### 🛡️ Administrator
- Secure login
- Dashboard with platform-wide statistics
- Manage users (view, block/unblock, delete)
- Manage vendors (approve, block, delete)
- View all products (toggle active/inactive)
- View all orders with detail view
- Update order status (confirmed → shipped → delivered)
- View all item requests from users
- Add additional admin accounts

---

## ⚙️ Requirements
- PHP 7.4+
- MySQL 5.7+ / MariaDB 10+
- Apache / Nginx web server
- XAMPP / WAMP / LAMP stack recommended

---

## 🔒 Security Features
- bcrypt password hashing
- SQL injection prevention via `real_escape_string`
- XSS prevention via `htmlspecialchars`
- Session-based authentication per role
- Role separation (admin/vendor/user completely isolated)

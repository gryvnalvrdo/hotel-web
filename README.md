# 🏨 Hotel Reservation & Management Web App

> A full-stack hotel booking and CMS system built with **Laravel 11** — featuring a multi-step booking engine, Midtrans payment gateway integration, and a complete Admin Dashboard.

**Note:** *This is a personal portfolio project and is not affiliated with any real hotel brand.*

---

## ✨ Features

| Module | Description |
|---|---|
| 🏠 **Landing Page** | Dynamic hero slider, room previews, facility gallery with lightbox pop-up, location map |
| 🛏️ **Room Catalog** | Listing of all room types with amenities, image sliders, and price per night |
| 🏢 **Conference & MICE** | Dedicated page for meeting room & event space information |
| 📝 **Booking Engine** | Multi-step flow: date availability check → room selection from live inventory → guest data & special request input → payment |
| 💳 **Payment Gateway** | Integrated with **Midtrans** (BCA VA, Mandiri VA, BNI VA, GoPay, OVO, DANA, QRIS, Credit Card) |
| 📊 **Admin Dashboard** | Full CMS: manage room catalog, home & room facilities, room inventory, guest reservations |
| 📱 **Fully Responsive** | Adaptive design from mobile to ultrawide monitor |

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.2+, Laravel 11, Eloquent ORM |
| **Frontend** | Blade Templating, Vanilla CSS (Modular), Vanilla JavaScript |
| **Icons** | Bootstrap Icons, Font Awesome |
| **Database** | SQLite *(default, zero-config)* · MySQL · PostgreSQL · SQL Server |
| **Payment** | Midtrans Payment Gateway (Sandbox) |

---

## 🗂️ Project Structure

```
hotel-web/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── RoomController.php
│   │   │   ├── ConferenceController.php
│   │   │   ├── BookingController.php
│   │   │   └── Admin/
│   │   │       ├── AuthController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── RoomController.php
│   │   │       ├── FacilityController.php
│   │   │       ├── InventoryController.php
│   │   │       └── BookingController.php
│   │   └── Middleware/
│   │       └── AdminAuth.php
│   └── Models/
│       ├── Room.php · RoomImage.php · RoomFacility.php
│       ├── Booking.php · Promo.php
│       ├── ConferenceRoom.php · ConferenceRoomImage.php
│       ├── HomeFacility.php · HomeFacilityImage.php · HomeSlider.php
│       └── Footer*.php (Branding, Contact, Social, Partner, Bottom)
├── database/
│   ├── migrations/         — Database schema definitions
│   ├── seeders/            — DatabaseSeeder.php (rooms, facilities, slider, etc.)
│   └── database.sqlite     — ⭐ Pre-seeded SQLite database (ready to use)
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php   — Main front-end layout
│   │   └── admin.blade.php — Admin dashboard layout
│   ├── home/               — Landing page sections
│   ├── rooms/              — Room catalog page
│   ├── conference/         — Conference & MICE page
│   ├── booking/            — Multi-step booking flow
│   └── admin/              — Dashboard, rooms, facilities, inventory, bookings
├── public/
│   ├── css/                — Modular CSS files (rooms, booking, admin, etc.)
│   ├── js/                 — Front-end JavaScript logic
│   └── images/             — Room photos, facility images, slider assets
└── routes/web.php          — All route definitions
```

---

## 🚀 Quick Start (< 1 Minute Setup)

This project uses **SQLite** by default — no database server installation required. The `database.sqlite` file is already pre-seeded with rooms, facilities, and demo data.

### 1. Clone the repository
```bash
git clone https://github.com/gryvnalvrdo/hotel-web.git
cd hotel-web
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Set up environment
```bash
cp .env.example .env
php artisan key:generate
```
> **Note:** No database configuration needed. Laravel 11 automatically uses the included `database/database.sqlite` file.

### 4. Run the development server
```bash
php artisan serve
```

Open **http://localhost:8000** in your browser — everything works immediately! 🎉

### 5. Admin Panel Access
Navigate to **http://localhost:8000/admin/login**

| Credential | Value |
|---|---|
| Username | `admin` |
| Password | `adminHotel123` |

---

## 🗺️ Routes Overview

| Method | Route | Description |
|---|---|---|
| GET | `/` | Landing page |
| GET | `/rooms` | Room catalog |
| GET | `/conference` | Conference & MICE page |
| GET | `/booking` | Booking step 1 (availability check) |
| GET | `/booking/check-availability` | AJAX availability check |
| POST | `/booking` | Submit booking |
| GET | `/booking/payment/{id}` | Payment page |
| POST | `/booking/simulate-pay/{id}` | Simulate payment (sandbox) |
| GET | `/booking/success/{id}` | Booking success page |
| GET | `/booking/invoice/{id}` | Guest invoice |
| POST | `/midtrans/callback` | Midtrans webhook callback |
| GET | `/admin` | Admin dashboard |
| — | `/admin/rooms` | Room CRUD management |
| — | `/admin/facilities` | Home & room facility management |
| — | `/admin/inventory` | Real-time inventory & occupancy |
| — | `/admin/bookings` | Guest reservation management |

---

## 💡 Cross-Database Support

Although SQLite is the default (zero-config), the app fully supports any Laravel-compatible database. Just update `.env`:

```env
# MySQL / MariaDB
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_web
DB_USERNAME=root
DB_PASSWORD=

# PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=hotel_web
DB_USERNAME=postgres
DB_PASSWORD=
```

Then run:
```bash
php artisan migrate --seed
```

---

## 📄 License

This project is built as an open-source portfolio and advanced web development learning exercise. Free to use for educational purposes.

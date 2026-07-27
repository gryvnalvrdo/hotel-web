# 🏨 Hotel Web App Project (Sistem Manajemen & Reservasi Hotel)

Ini adalah **Proyek Web Hotel** berskala penuh yang saya bangun menggunakan ekosistem modern Laravel 10. Proyek ini berfungsi sebagai portofolio/sistem *booking* hotel yang komprehensif, mencakup *user-facing booking flow* dan *Admin Dashboard* (CMS) untuk mengelola ketersediaan, tipe kamar, serta inventaris kamar.

**Catatan:** *Aplikasi ini murni merupakan sebuah proyek web dan tidak terafiliasi dengan brand hotel nyata manapun di dunia nyata.*

## Tech Stack & Kompatibilitas Database

Aplikasi ini menggunakan **Eloquent ORM** dari Laravel, yang berarti sistem ini **sudah otomatis mendukung berbagai macam tipe database (Cross-Database Support)**:
- **MySQL** / MariaDB (Default)
- **PostgreSQL**
- **SQLite**
- **SQL Server**

Anda hanya perlu mengubah kredensial `DB_CONNECTION` di dalam file `.env`, dan Laravel akan menangani kuerinya.

- **PHP** 8.1+
- **Laravel** 10
- **Blade** templating
- **Vanilla CSS** (Responsive & Modular)
- **JavaScript Vanilla** (Interaktif tanpa jQuery)
- **Bootstrap Icons** + **Font Awesome**

---

## Fitur Utama

- 🏠 **Landing Page (Home)** — Hero slider dinamis, pratinjau kamar, fasilitas utama, dan peta lokasi yang elegan.
- 🛏️ **Katalog Kamar** — Detail kamar, slider foto interaktif (modal popup), fasilitas per kategori.
- 🏢 **Conference & Meeting** — Manajemen halaman ruang *meeting* (MICE).
- 📝 **Booking Engine (Multi-step)** — Alur pemesanan yang canggih (Cek ketersediaan tanggal → Pilih kamar dari grid responsif → Input data tamu & permintaan khusus → Konfirmasi).
- 📊 **Admin Dashboard (CMS)** — Manajemen master kamar, kontrol inventaris per lantai, validasi pemesanan tamu.
- 📱 **Fully Responsive** — Desain adaptif dari Mobile hingga layar Monitor ultrawide.
- ✨ **Global CSS System** — Sistem desain yang rapi tanpa redudansi CSS antar halaman.

---

## Setup Lokal

### 1. Clone repository
```bash
git clone https://github.com/gryvnalvrdo/hotel-web.git
cd hotel-web
```

### 2. Install dependencies
```bash
composer install
npm install (jika diperlukan)
```

### 3. Copy & konfigurasi .env
```bash
cp .env.example .env
```

Edit `.env` dan sesuaikan dengan environment Anda:
```env
APP_NAME="Web Hotel"
APP_URL=http://localhost:8000

# Ubah DB_CONNECTION sesuai database yang Anda gunakan (mysql/pgsql/sqlite)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_web
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate app key & Migrasi Database
```bash
php artisan key:generate
php artisan migrate
```
*(Opsional: Anda bisa menggunakan file import SQL raw jika menggunakan XAMPP tanpa CLI).*

### 5. Jalankan server
```bash
php artisan serve
```

Buka `http://localhost:8000` di browser Anda 🎉.

---

## Struktur Proyek

```
hotel-web/
├── app/
│   ├── Http/Controllers/   → HomeController, RoomController, BookingController, dll
│   └── Models/             → Room, Booking, Promo, dll (Eloquent Models)
├── resources/views/
│   ├── layouts/app.blade.php   → Layout utama front-end
│   ├── layouts/admin.blade.php → Layout dashboard backend
│   ├── booking/                → Alur pemesanan kamar
│   └── admin/                  → Seluruh antarmuka admin
├── public/
│   ├── css/                    → Modular CSS
│   ├── js/                     → Front-end logic
│   └── images/                 → Assets
└── routes/web.php          → Deklarasi routing
```

---

## License

Proyek ini dibangun sebagai portofolio open-source dan pembelajaran web development tingkat lanjut.

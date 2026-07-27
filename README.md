# 🏨 Hotel Web App Project (Sistem Manajemen & Reservasi Hotel)

Ini adalah **Proyek Web Hotel** berskala penuh yang dibangun menggunakan ekosistem modern Laravel 11. Proyek ini berfungsi sebagai portofolio/sistem *booking* hotel yang komprehensif, mencakup *user-facing booking flow* dan *Admin Dashboard* (CMS) untuk mengelola ketersediaan, tipe kamar, fasilitas, serta inventaris kamar.

**Catatan:** *Aplikasi ini murni merupakan proyek web portofolio buatan saya pribadi dan tidak terafiliasi dengan brand hotel nyata manapun di dunia nyata.*

## Tech Stack Modern & Database Terpadu

Aplikasi ini menggunakan **SQLite** secara bawaan (*default*). Ini adalah standar industri modern untuk aplikasi portofolio karena:
- **TIDAK PERLU INSTALL XAMPP**
- **TIDAK PERLU PHPMyAdmin**
- **TIDAK PERLU Import/Export file SQL**
- Seluruh database sudah tersimpan rapi dan abadi di dalam file `database/database.sqlite`. Anda cukup *clone* dan jalankan!

Tentu saja, karena menggunakan **Eloquent ORM** dari Laravel, sistem ini juga **mendukung berbagai macam tipe database (Cross-Database Support)** jika Anda ingin mengubahnya:
- **MySQL** / MariaDB 
- **PostgreSQL**
- **SQL Server**

- **PHP** 8.2+
- **Laravel** 11
- **Blade** templating
- **Vanilla CSS** (Responsive & Modular)
- **JavaScript Vanilla** (Interaktif tanpa jQuery)
- **Bootstrap Icons** + **Font Awesome**

---

## Fitur Utama

- 🏠 **Landing Page (Home)** — Hero slider dinamis, pratinjau kamar, galeri fasilitas (Lightbox Pop-up), dan peta lokasi yang elegan.
- 🛏️ **Katalog Kamar** — Detail kamar, fasilitas per kategori.
- 🏢 **Conference & Meeting** — Manajemen halaman ruang *meeting* (MICE).
- 📝 **Booking Engine (Multi-step)** — Alur pemesanan yang canggih (Cek ketersediaan tanggal → Pilih kamar dari grid responsif → Input data tamu & permintaan khusus → Konfirmasi).
- 📊 **Admin Dashboard (CMS)** — Manajemen master kamar, manajemen fasilitas umum & kamar, kontrol inventaris, serta validasi pemesanan tamu.
- 📱 **Fully Responsive** — Desain adaptif dari Mobile hingga layar Monitor ultrawide.
- ✨ **Global CSS System** — Sistem desain yang rapi dan elegan.

---

## Setup Lokal (Super Instan)

Karena menggunakan **SQLite**, Anda bisa menjalankan aplikasi ini kurang dari 1 menit!

### 1. Clone repository
```bash
git clone https://github.com/gryvnalvrdo/hotel-web.git
cd hotel-web
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Copy .env
```bash
cp .env.example .env
```
*(Catatan: Anda tidak perlu mengkonfigurasi bagian database di `.env` karena secara default Laravel 11 akan membaca koneksi SQLite dan file `database.sqlite` yang sudah disertakan).*

### 4. Generate app key & Jalankan server
```bash
php artisan key:generate
php artisan serve
```

Buka `http://localhost:8000` di browser Anda 🎉.
Aplikasi akan langsung menampilkan data kamar, fasilitas, dan gambar tanpa konfigurasi tambahan!

---

## Struktur Proyek

```
hotel-web/
├── app/
│   ├── Http/Controllers/   → HomeController, RoomController, BookingController, dll
│   └── Models/             → Room, Booking, Promo, dll (Eloquent Models)
├── database/
│   ├── migrations/         → Skema struktur database
│   ├── seeders/            → File seeder penyuntik data dummy
│   └── database.sqlite     → 🌟 File Database Fisik (Siap pakai)
├── resources/views/
│   ├── layouts/app.blade.php   → Layout utama front-end
│   ├── layouts/admin.blade.php → Layout dashboard backend
│   ├── booking/                → Alur pemesanan kamar
│   └── admin/                  → Seluruh antarmuka admin
├── public/
│   ├── css/                    → Modular CSS
│   ├── js/                     → Front-end logic
│   └── images/                 → Assets (Gambar ruangan, fasilitas, logo, dll)
└── routes/web.php          → Deklarasi routing
```

---

## License

Proyek ini dibangun sebagai portofolio *open-source* dan pembelajaran web development tingkat lanjut.

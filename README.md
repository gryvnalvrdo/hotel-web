# 🏨 Grand Lumina — Laravel Web App

Website resmi **Grand Lumina Hotel** — dibangun dengan Laravel 10, Blade Templates, dan Eloquent ORM.

## Tech Stack

- **PHP** 8.1+
- **Laravel** 10
- **MySQL** (via XAMPP lokal / PlanetScale untuk cloud)
- **Blade** templating
- **Vanilla CSS** (global.css + per-page CSS)
- **Bootstrap Icons** + **Font Awesome**

---

## Fitur

- 🏠 **Home** — Hero slider, Rooms preview, Facilities, Peta lokasi
- 🛏️ **Rooms** — Detail kamar, slider foto, modal popup, fasilitas per kategori
- 🏢 **Conference** — Ruang meeting dengan slider & lightbox
- 📝 **Booking** — Form multi-step (tanggal → pilih kamar → info tamu)
- 📱 **Responsive** — Mobile-friendly di semua halaman
- ✨ **Global CSS** — Token warna konsisten, tidak ada duplikasi antar halaman

---

## Setup Lokal

### 1. Clone repository
```bash
git clone https://github.com/username/hotel-Lumina.git
cd hotel-Lumina
```

### 2. Install dependencies
```bash
composer install
```

### 3. Copy & konfigurasi .env
```bash
cp .env.example .env
```

Edit `.env` dan isi:
```env
APP_NAME="Grand Lumina"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Lumina         # nama database kamu
DB_USERNAME=root
DB_PASSWORD=              # password MySQL kamu
```

### 4. Generate app key
```bash
php artisan key:generate
```

### 5. Import database

Buka **phpMyAdmin** → import file `database/Lumina.sql` (ekspor dulu dari XAMPP lama).

Atau kalau pakai **PlanetScale**:
```bash
# Install PlanetScale CLI, lalu:
pscale database create hotel-Lumina
pscale shell hotel-Lumina main < database/Lumina.sql
```

### 6. Jalankan server
```bash
php artisan serve
```

Buka `http://localhost:8000` 🎉

---

## Struktur Project

```
hotel-Lumina/
├── app/
│   ├── Http/Controllers/   → HomeController, RoomController, dll
│   └── Models/             → Room, Booking, dll (Eloquent)
├── resources/views/
│   ├── layouts/app.blade.php   → Layout utama (navbar + footer)
│   ├── home/index.blade.php
│   ├── rooms/index.blade.php
│   ├── conference/index.blade.php
│   └── booking/index.blade.php
├── public/
│   ├── css/global.css      → CSS global (token, navbar, footer, dll)
│   ├── css/home.css        → CSS spesifik halaman home
│   ├── js/                 → JavaScript per halaman
│   └── images/             → Semua gambar
└── routes/web.php          → Semua routing
```

---

## Deploy ke Cloud

### PlanetScale (Database)
1. Buat akun di [planetscale.com](https://planetscale.com)
2. Buat database baru
3. Import SQL dari `database/Lumina.sql`
4. Copy connection string ke `.env`

### Railway / Render (Hosting PHP)
1. Connect GitHub repo
2. Set environment variables
3. Deploy otomatis setiap `git push`!

---

## Database Tables

| Tabel | Keterangan |
|-------|-----------|
| `rooms` | Data kamar hotel |
| `room_images` | Foto-foto kamar |
| `room_facilities` | Fasilitas kamar per kategori |
| `conference_rooms` | Ruang meeting |
| `conference_room_images` | Foto ruang meeting |
| `home_slider` | Foto hero slider halaman home |
| `home_facilities` | Fasilitas hotel di home |
| `bookings` | Data pemesanan tamu |
| `footer_*` | Data footer (branding, social, partners, contact) |

---

## License

© 2025 Grand Lumina Hotel. All rights reserved.

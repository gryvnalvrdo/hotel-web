# Hospitality Reservation & Property ERP UI/UX Skill (`skills.md`)

> **Target Application**: **Web Hotel / Hotel Claro Makassar Reservation & Management System** (`hotel-web-iota-pink.vercel.app`)  
> **Product Domain**: Hospitality E-Commerce Storefront, Multi-Room Reservation Engine, Payment Gateway Integration & Back-Office Property Management CMS (ERP).  
> **Tech Stack**: Laravel 11 (PHP MVC), Blade Templates, Eloquent ORM, Relational SQL Database, Midtrans Payment Gateway, Responsive Vanilla CSS/JS, Bootstrap Icons & Font Awesome.

---

## 1. Domain Identity & Visual Theme (Hospitality Luxury & Transactional ERP)

Berbeda dari aplikasi SaaS developer atau portofolio teknis, web ini adalah **Sistem Reservasi Perhotelan & Manajemen Properti (Hospitality E-Commerce & ERP)** yang melayani dua jenis pengguna sekaligus: **Tamu Hotel (Guest Storefront)** dan **Staf Operasional/Resepsionis (Admin Back-Office)**.

### 1.1 Sistem Warna & Tipografi Asli (`public/css/global.css`)
- **Deep Ocean Luxury Navy Palette**:
  - `--navy`: `#0C1526`
  - `--navy-dark`: `#08101E`
  - `--navy-mid`: `#0A1628`
  - `--navy-2`: `#132035`
  - `--navy-3`: `#1A2D47`
- **Hospitality Gold Accent Palette**:
  - `--gold`: `#C9962A`
  - `--gold-light`: `#E8B84B`
  - `--gold-dark`: `#9A7220`
  - `--gold-muted`: `rgba(201, 150, 42, 0.15)`
- **Pasangan Tipografi**:
  - Heading Editorial & Nama Kamar: `'Playfair Display', serif` (memberikan nuansa elegan khas perhotelan bintang lima).
  - Form Reservasi, Tabel Admin & Navigasi: `'Poppins', sans-serif` (menjaga keterbacaan harga, tanggal check-in/check-out, dan kontrol inventaris).

---

## 2. Aturan Anti-AI-Slop & Gaya Bahasa Hospitality

1. **Bebas Tanda Hubung AI (*Em-Dash* / *En-Dash*) & Simbol Aneh**:
   - Dilarang memakai tanda hubung panjang (`—` atau `–`) atau titik tengah (`·`) yang berlebihan pada judul halaman (`<title>`), deskripsi meta, maupun kartu fasilitas.
   - Gunakan tanda pisah standar (`|` atau `,`) yang bersih dan alami.
2. **Zero Redundancy pada Alur Pemesanan (Booking Funnel)**:
   - Jangan mengulang elemen kontak atau tombol *Book Now* secara berlebihan di tempat yang berdekatan.
   - Pada Navbar: Sediakan navigasi terstruktur (`Home`, `Rooms`, `Facilities`, `Conference`, `Book Now`, dan akses `Admin`).
   - Pada halaman `Booking`: Fokuskan perhatian tamu pada pemilihan tanggal (`Check-in` & `Check-out`), ketersediaan stok unit kamar secara real-time, kapasitas tamu, ringkasan harga multi-kamar, kode promo, dan metode pembayaran (`Midtrans` atau `Pay at Hotel`).

---

## 3. Arsitektur Transaksional & Keahlian Spesifik Sistem Hotel

Setiap perubahan pada repositori ini wajib menjaga integritas logika bisnis perhotelan berikut:

1. **Multi-Room Availability & Capacity Validation (`BookingController.php`)**:
   - Validasi ketersediaan stok kamar (`isRoomAvailable` & `getAvailableStock($checkin, $checkout)`) wajib mengecek irisan tanggal reservasi yang aktif agar tidak terjadi *overbooking*.
   - Validasi kapasitas total kamar (`capacity * qty`) wajib memastikan jumlah tamu (`guests`) tidak melebihi kapasitas unit yang dipesan.
2. **Keamanan Kalkulasi Harga & Kode Promo di Server**:
   - Total tagihan (`total_price`) wajib dihitung ulang di sisi server berdasarkan harga kamar di database dikalikan jumlah unit dan jumlah malam (`nights`), dikurangi diskon promo yang valid (`Promo::where('code', ...)`), bukan sekadar mempercayai input dari client.
3. **Integrasi Pembayaran Midtrans & Cetak Invoice**:
   - Mendukung pembayaran instan via **Midtrans** (beserta webhook callback `/midtrans/callback` tanpa blokir CSRF) dan opsi **Pay at Hotel**, dilengkapi pembuatan invoice (`/booking/invoice/{id}`) serta pengiriman email konfirmasi (`BookingConfirmedMail`).
4. **Admin Back-Office CMS (`AdminAuth` Middleware)**:
   - Menyediakan manajemen inventaris harian (`inventory/quick-update`), CRUD tipe kamar & galeri foto (`Admin\RoomController`), CRUD fasilitas (`Admin\FacilityController`), serta pembaruan status reservasi tamu (`Admin\BookingController`).
5. **Mobile-First Guest & Staff UX**:
   - Navigasi mobile menggunakan *slide-out drawer* (`header_footer.js`) yang responsif, galeri foto mendukung *lazy-loading* dengan transisi halus, dan form pemesanan kamar nyaman diisi melalui layar smartphone.

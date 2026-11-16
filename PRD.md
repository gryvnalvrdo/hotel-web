# Product Requirements Document (PRD)
## Web Hotel (Hotel Claro Makassar): Reservation, Payment Gateway & Property Management ERP

**Nama Sistem**: Web Hotel Reservation & Property Management System (`hotel-web-iota-pink.vercel.app`)  
**Kategori Produk**: Hospitality E-Commerce Storefront, Multi-Room Booking Engine & Admin ERP/CMS  
**Stack Utama**: Laravel 11 (PHP MVC), Blade Templates, Relational SQL Database, Midtrans Payment Gateway, Linux Production Server

---

## 1. Gambaran Umum & Tujuan Sistem

Sistem ini dikembangkan untuk menjawab kebutuhan operasional dan reservasi digital perhotelan secara end-to-end. Berbeda dari situs profil hotel statis, aplikasi ini menggabungkan **Storefront Tamu Berdesain Luxury (Deep Ocean Navy & Gold)**, **Mesin Pemesanan Multi-Kamar dengan Validasi Stok Real-Time**, **Integrasi Pembayaran Midtrans**, serta **Portal Admin Back-Office** untuk mengelola ketersediaan kamar, harga, fasilitas, dan status pesanan.

---

## 2. Spesifikasi Arsitektur & Modul Fitur

### 2.1 Modul Storefront & Eksplorasi Tamu
- **Home (`/`)**: Hero slider interaktif, kurasi kamar unggulan (*Favorite Rooms*), galeri fasilitas dengan modal *image carousel*, dan integrasi peta lokasi.
- **Katalog Kamar (`/rooms`)**: Daftar lengkap tipe kamar (*Superior, Deluxe, Junior Suite, Executive/Family, Presidential Suite*) beserta kapasitas tamu, harga per malam, dan fasilitas kamar.
- **Ruang Konferensi & MICE (`/conference`)**: Informasi paket ruang rapat, ballroom, dan fasilitas acara korporat.

### 2.2 Mesin Reservasi Multi-Kamar & Validasi Stok (`/booking`)
- **Real-Time Date-Range Availability (`GET /booking/check-availability`)**: Mengecek sisa stok kamar yang tersedia (`getAvailableStock`) berdasarkan rentang tanggal *check-in* dan *check-out* yang dipilih tamu.
- **Multi-Room Cart (`room_details`)**: Memungkinkan tamu memesan lebih dari satu tipe atau unit kamar dalam satu transaksi sekaligus.
- **Validasi Kapasitas Tamu**: Menolak pemesanan secara otomatis dengan pesan informatif apabila jumlah tamu (`guests`) melebihi total kapasitas kamar yang dipilih.
- **Mesin Diskon & Kode Promo**: Mendukung validasi kode promo aktif dengan potongan berbasis persentase (`percent`) maupun nominal tetap.

### 2.3 Pembayaran Midtrans, Invoice & Notifikasi Email
- **Dual Payment Flow**:
  1. **Midtrans Online Payment** (`/booking/payment/{id}` & webhook callback `POST /midtrans/callback`).
  2. **Pay at Hotel** (konfirmasi instan dengan status `pay_at_hotel`).
- **Digital Invoice & Email**: Menghasilkan halaman bukti reservasi (`/booking/success/{id}`), cetak invoice resmi (`/booking/invoice/{id}`), dan mengirim email konfirmasi otomatis (`BookingConfirmedMail`).

### 2.4 Portal Admin Back-Office (`/admin/*` dengan Middleware `AdminAuth`)
- **Dashboard Operasional (`/admin`)**: Ringkasan pesanan masuk, okupansi, dan pendapatan.
- **Manajemen Inventaris Cepat (`/admin/inventory`)**: Fitur *quick-update* untuk mengatur ketersediaan stok unit kamar secara langsung.
- **CRUD Kamar & Fasilitas (`/admin/rooms` & `/admin/facilities`)**: Pengelolaan detail kamar, unggah multi-foto, serta pengaturan fasilitas utama dan fasilitas kamar.
- **Manajemen Reservasi (`/admin/bookings`)**: Pemantauan detail pesanan tamu, pembaruan status reservasi, dan administrasi pembatalan.

---

## 3. Standar UI/UX & Kualitas Teknis

- **Identitas Visual Hospitality**: Mempertahankan kontras elegan antara latar **Deep Ocean Navy (`#0C1526`)**, aksen **Warm Gold (`#C9962A`)**, dan tipografi *Playfair Display* + *Poppins*.
- **Bebas Tanda Baca AI**: Seluruh judul halaman, deskripsi meta, dan elemen antarmuka bebas dari tanda hubung panjang khas AI (*em-dash* / *en-dash*) atau simbol dekoratif yang berlebihan.
- **Performa & Mobile UX**: Menggunakan *preconnect* CDN, *lazy-loading* gambar dengan `IntersectionObserver`, serta menu navigasi *drawer* yang responsif di layar smartphone.

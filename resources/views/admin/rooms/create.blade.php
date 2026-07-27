@extends('layouts.admin')

@section('title', 'Tambah Kamar Baru — Admin Grand Lumina')
@section('page-title', 'Tambah Katalog Kamar Baru')

@section('content')

  <div class="card" style="max-width:800px;">
    <div class="card-header">
      <h3>Formulir Tipe Kamar Baru</h3>
      <a href="{{ route('admin.rooms.index') }}" class="btn btn-navy"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label for="name">Nama Tipe Kamar <span style="color:#b91c1c;">*</span></label>
        <input type="text" id="name" name="name" class="form-control" required placeholder="Contoh: Royal Suite, Deluxe Ocean View, dll." value="{{ old('name') }}">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1.2fr;gap:20px;">
        <div class="form-group">
          <label for="price">Tarif per Malam (Rp) <span style="color:#b91c1c;">*</span></label>
          <input type="number" id="price" name="price" class="form-control" required min="0" step="50000" placeholder="Contoh: 1500000" value="{{ old('price') }}">
        </div>

        <div class="form-group">
          <label for="capacity">Kapasitas Tamu <span style="color:#b91c1c;">*</span></label>
          <select id="capacity" name="capacity" class="form-control" required>
            <option value="1">1 Tamu</option>
            <option value="2" selected>2 Tamu</option>
            <option value="3">3 Tamu</option>
            <option value="4">4 Tamu</option>
            <option value="5">5 Tamu / Lebih</option>
          </select>
        </div>

        <div class="form-group">
          <label for="total_rooms">Total Stok <span style="color:#b91c1c;">*</span></label>
          <input type="number" id="total_rooms" name="total_rooms" class="form-control" required min="1" placeholder="Contoh: 20" value="{{ old('total_rooms', 20) }}">
        </div>

        <div class="form-group">
          <label for="floor_location">Lokasi & Lantai Kamar</label>
          <input type="text" id="floor_location" name="floor_location" class="form-control" placeholder="Contoh: Lantai 3 - 8 (Tower Utama)" value="{{ old('floor_location', 'Lantai 3 - 8 (Tower Utama)') }}">
        </div>
      </div>

      <div class="form-group">
        <label for="short_description">Deskripsi Singkat (Muncul di Katalog)</label>
        <input type="text" id="short_description" name="short_description" class="form-control" placeholder="Kamar mewah dengan pemandangan kota dan kenyamanan eksklusif." value="{{ old('short_description') }}">
      </div>

      <div class="form-group">
        <label for="full_description">Deskripsi Lengkap (Muncul di Pop-up Detail)</label>
        <textarea id="full_description" name="full_description" class="form-control" rows="4" placeholder="Jelaskan keunggulan kamar, tempat tidur, kamar mandi, serta layanan sarapan dan Wi-Fi gratis...">{{ old('full_description') }}</textarea>
      </div>

      <div class="form-group">
        <label for="image">Foto Utama Kamar (Opsional - Format JPG/PNG, Maks 2MB)</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
        <small style="color:#64748b;display:block;margin-top:6px;">Jika tidak diisi, sistem otomatis menggunakan gambar default hotel yang beresolusi tinggi.</small>
      </div>

      <div style="margin-top:32px;border-top:1px solid var(--border);padding-top:20px;">
        <button type="submit" class="btn btn-gold" style="padding:14px 28px;font-size:1rem;"><i class="bi bi-save2-fill"></i> Simpan & Daftarkan Kamar</button>
      </div>
    </form>
  </div>

@endsection

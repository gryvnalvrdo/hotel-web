@extends('layouts.admin')

@section('title', 'Edit Harga & Data Kamar — Admin Grand Lumina')
@section('page-title', 'Edit Data Tipe Kamar')

@section('content')

  <div class="card" style="max-width:800px;">
    <div class="card-header">
      <h3>Edit Tipe Kamar: {{ $room->name }}</h3>
      <a href="{{ route('admin.rooms.index') }}" class="btn btn-navy"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="name">Nama Tipe Kamar <span style="color:#b91c1c;">*</span></label>
        <input type="text" id="name" name="name" class="form-control" required value="{{ old('name', $room->name) }}">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1.2fr;gap:20px;">
        <div class="form-group">
          <label for="price">Tarif per Malam (Rp) <span style="color:#b91c1c;">*</span></label>
          <input type="number" id="price" name="price" class="form-control" required min="0" step="50000" value="{{ old('price', $room->price) }}">
        </div>

        <div class="form-group">
          <label for="capacity">Kapasitas Tamu <span style="color:#b91c1c;">*</span></label>
          <select id="capacity" name="capacity" class="form-control" required>
            @for($i=1; $i<=5; $i++)
              <option value="{{ $i }}" {{ $room->capacity == $i ? 'selected' : '' }}>{{ $i }} Tamu</option>
            @endfor
          </select>
        </div>

        <div class="form-group">
          <label for="total_rooms">Total Stok <span style="color:#b91c1c;">*</span></label>
          <input type="number" id="total_rooms" name="total_rooms" class="form-control" required min="1" value="{{ old('total_rooms', $room->total_rooms ?: 20) }}">
        </div>

        <div class="form-group">
          <label for="floor_location">Lokasi & Lantai Kamar</label>
          <input type="text" id="floor_location" name="floor_location" class="form-control" placeholder="Contoh: Lantai 3 - 8" value="{{ old('floor_location', $room->floor_location ?: 'Lantai 3 - 8 (Tower Utama)') }}">
        </div>
      </div>

      <div class="form-group">
        <label for="short_description">Deskripsi Singkat</label>
        <input type="text" id="short_description" name="short_description" class="form-control" value="{{ old('short_description', $room->short_description) }}">
      </div>

      <div class="form-group">
        <label for="full_description">Deskripsi Lengkap</label>
        <textarea id="full_description" name="full_description" class="form-control" rows="4">{{ old('full_description', $room->full_description) }}</textarea>
      </div>

      <div class="form-group">
        <label>Foto Utama Saat Ini:</label>
        <div style="margin-bottom:12px;">
          <img src="{{ asset($room->images->first()?->file_path ?? 'images/slider/slider1.jpg') }}" style="width:160px;height:110px;object-fit:cover;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,0.1);" alt="{{ $room->name }}">
        </div>
        <label for="image">Ganti Foto Utama (Opsional):</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
      </div>

      <div style="margin-top:32px;border-top:1px solid var(--border);padding-top:20px;display:flex;gap:14px;">
        <button type="submit" class="btn btn-gold" style="padding:14px 28px;font-size:1rem;"><i class="bi bi-save2-fill"></i> Simpan Perubahan Harga</button>
      </div>
    </form>
  </div>

@endsection

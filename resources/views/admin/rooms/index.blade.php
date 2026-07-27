@extends('layouts.admin')

@section('title', 'Manajemen Kamar — Admin Web Hotel')
@section('page-title', 'Manajemen Katalog Kamar')

@section('content')

  <div class="card">
    <div class="card-header">
      <h3>Daftar Tipe Kamar & Tarif Resmi</h3>
      <a href="{{ route('admin.rooms.create') }}" class="btn btn-gold"><i class="bi bi-plus-circle-fill"></i> Tambah Tipe Kamar Baru</a>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Foto Utama</th>
            <th>Nama Tipe Kamar</th>
            <th>Tarif / Malam</th>
            <th>Kapasitas</th>
            <th>Total Stok</th>
            <th>Lokasi & Lantai</th>
            <th>Fasilitas</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rooms as $r)
            <tr>
              <td><strong style="color:#64748b;">#{{ $r->id }}</strong></td>
              <td>
                <img src="{{ asset($r->images->first()?->file_path ?? 'images/slider/slider1.jpg') }}" style="width:80px;height:55px;object-fit:cover;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.1);" alt="{{ $r->name }}">
              </td>
              <td>
                <strong style="font-size:1.08rem;color:#0F172A;display:block;">{{ $r->name }}</strong>
                <span style="font-size:0.8rem;color:#64748b;">{{ Str::limit($r->short_description, 50) }}</span>
              </td>
              <td>
                <strong style="color:#9E7D3B;font-size:1.15rem;">Rp {{ number_format($r->price, 0, ',', '.') }}</strong>
              </td>
              <td>
                <span style="background:#F8F6F0;padding:5px 12px;border-radius:12px;font-weight:700;color:#0F172A;"><i class="bi bi-person-fill" style="color:#C5A059;"></i> {{ $r->capacity }} Tamu</span>
              </td>
              <td>
                <span style="background:#e0f2fe;padding:5px 12px;border-radius:12px;font-weight:700;color:#0369a1;"><i class="bi bi-building"></i> {{ $r->total_rooms ?: 20 }} Kamar</span>
              </td>
              <td>
                <span style="background:#f1f5f9;padding:5px 12px;border-radius:12px;font-size:0.85rem;color:#334155;font-weight:600;"><i class="bi bi-geo-alt-fill" style="color:#C5A059;"></i> {{ $r->floor_location ?: 'Lantai 3 - 8' }}</span>
              </td>
              <td>
                <span style="font-size:0.85rem;color:#475569;">{{ $r->facilities->count() }} Fasilitas Terdaftar</span>
              </td>
              <td>
                <div style="display:flex;gap:8px;">
                  <a href="{{ route('admin.rooms.edit', $r->id) }}" class="btn btn-navy" style="padding:8px 14px;"><i class="bi bi-pencil-square"></i> Edit Harga</a>
                  <form action="{{ route('admin.rooms.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tipe kamar ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding:8px 12px;"><i class="bi bi-trash3-fill"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align:center;padding:40px;color:#64748b;">Belum ada kamar terdaftar. Silakan klik tombol Tambah Tipe Kamar Baru.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection

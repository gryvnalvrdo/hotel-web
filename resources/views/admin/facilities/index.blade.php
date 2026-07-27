@extends('layouts.admin')

@section('title', 'Manajemen Fasilitas — Admin Web Hotel')
@section('page-title', 'Manajemen Fasilitas Hotel & Kamar')

@section('content')

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">
    
    {{-- FASILITAS UMUM HOTEL --}}
    <div>
      <div class="card" style="margin-bottom:28px;">
        <div class="card-header">
          <h3>Tambah Fasilitas Umum Hotel</h3>
        </div>
        <form action="{{ route('admin.facilities.storeHome') }}" method="POST">
          @csrf
          <div class="form-group">
            <label>Nama Fasilitas (Contoh: Sky Lounge, Infinity Pool, Ballroom)</label>
            <input type="text" name="title" class="form-control" required placeholder="Nama fasilitas...">
          </div>
          <div class="form-group">
            <label>Icon Class (Bootstrap Icons)</label>
            <input type="text" name="icon" class="form-control" placeholder="bi bi-stars" value="bi bi-stars">
            <small style="color:#64748b;">Contoh: bi bi-water, bi bi-cup-hot, bi bi-building, bi bi-wifi</small>
          </div>
          <div class="form-group">
            <label>Deskripsi Singkat</label>
            <textarea name="description" class="form-control" rows="2" required placeholder="Fasilitas eksklusif berstandar internasional..."></textarea>
          </div>
          <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;"><i class="bi bi-plus-circle-fill"></i> Tambah Fasilitas Hotel</button>
        </form>
      </div>

      <div class="card">
        <div class="card-header">
          <h3>Daftar Fasilitas Umum Hotel ({{ $homeFacilities->count() }})</h3>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Icon</th>
                <th>Nama Fasilitas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($homeFacilities as $hf)
                <tr>
                  <td><i class="{{ $hf->icon ?? 'bi bi-stars' }}" style="font-size:1.3rem;color:#C5A059;"></i></td>
                  <td>
                    <strong style="color:#0F172A;display:block;">{{ $hf->title }}</strong>
                    <span style="font-size:0.8rem;color:#64748b;">{{ Str::limit($hf->description, 35) }}</span>
                  </td>
                  <td>
                    <form action="{{ route('admin.facilities.destroyHome', $hf->id) }}" method="POST" onsubmit="return confirm('Hapus fasilitas umum ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger" style="padding:6px 10px;"><i class="bi bi-trash3-fill"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="3" style="text-align:center;color:#64748b;">Belum ada fasilitas umum terdaftar.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- FASILITAS KAMAR --}}
    <div>
      <div class="card" style="margin-bottom:28px;">
        <div class="card-header">
          <h3>Tambah Fasilitas Kamar</h3>
        </div>
        <form action="{{ route('admin.facilities.storeRoom') }}" method="POST">
          @csrf
          <div class="form-group">
            <label>Pilih Tipe Kamar</label>
            <select name="room_id" class="form-control" required>
              @foreach($rooms as $r)
                <option value="{{ $r->id }}">{{ $r->name }} (Rp {{ number_format($r->price, 0, ',', '.') }})</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Nama Fasilitas Kamar</label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: Smart LED TV 50-inch, Coffee Maker, dll.">
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div class="form-group">
              <label>Icon Class</label>
              <input type="text" name="icon" class="form-control" placeholder="bi bi-tv" value="bi bi-check2-circle">
            </div>
            <div class="form-group">
              <label>Kategori</label>
              <select name="category" class="form-control">
                <option value="utama">Utama (Badge)</option>
                <option value="kamar" selected>Fasilitas Kamar</option>
                <option value="mandi">Kamar Mandi</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-navy" style="width:100%;justify-content:center;"><i class="bi bi-plus-circle-fill"></i> Tambah Fasilitas Kamar</button>
        </form>
      </div>

      <div class="card">
        <div class="card-header">
          <h3>Daftar Fasilitas Kamar Terakhir</h3>
        </div>
        <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
          <table class="table">
            <thead>
              <tr>
                <th>Tipe Kamar</th>
                <th>Nama Fasilitas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($roomFacilities->take(20) as $rf)
                <tr>
                  <td><strong style="color:#0F172A;font-size:0.85rem;">{{ $rf->room?->name }}</strong></td>
                  <td>
                    <i class="{{ $rf->icon ?? 'bi bi-check2-circle' }}" style="color:#C5A059;margin-right:6px;"></i>
                    <span>{{ $rf->facility_name ?? $rf->name }}</span>
                  </td>
                  <td>
                    <form action="{{ route('admin.facilities.destroyRoom', $rf->id) }}" method="POST" onsubmit="return confirm('Hapus fasilitas kamar ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger" style="padding:5px 8px;"><i class="bi bi-trash3-fill"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="3" style="text-align:center;color:#64748b;">Belum ada fasilitas kamar.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

@endsection

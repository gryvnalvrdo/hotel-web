@extends('layouts.admin')

@section('title', 'Manajemen Fasilitas — Admin Web Hotel')
@section('page-title', 'Manajemen Fasilitas Hotel & Kamar')

@section('content')

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">
    
    {{-- BAGIAN KIRI: FASILITAS UMUM HOTEL --}}
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
                <th>Preview</th>
                <th>Nama Fasilitas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($homeFacilities as $hf)
                <tr>
                  <td>
                    @if($hf->images && $hf->images->count() > 0)
                      <img src="{{ asset($hf->images->first()->image_path) }}" alt="{{ $hf->title }}" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                    @else
                      <div style="width:50px;height:50px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;"><i class="bi bi-image"></i></div>
                    @endif
                  </td>
                  <td>
                    <strong style="color:#0F172A;display:block;">{{ $hf->title }}</strong>
                    <span style="font-size:0.8rem;color:#64748b;">{{ Str::limit($hf->description, 40) }}</span>
                  </td>
                  <td>
                    <div style="display:flex; gap:6px;">
                      <button type="button" class="btn btn-navy" style="padding:6px 10px;" onclick="openEditHomeModal({{ $hf->id }}, '{{ addslashes($hf->title) }}', '{{ addslashes($hf->description) }}')">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <form action="{{ route('admin.facilities.destroyHome', $hf->id) }}" method="POST" onsubmit="return confirm('Hapus fasilitas umum ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:6px 10px;"><i class="bi bi-trash3-fill"></i></button>
                      </form>
                    </div>
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

    {{-- BAGIAN KANAN: FASILITAS KAMAR --}}
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
                <option value="{{ $r->id }}">{{ $r->name }}</option>
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
                <option value="bathroom">Kamar Mandi</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-navy" style="width:100%;justify-content:center;"><i class="bi bi-plus-circle-fill"></i> Tambah Fasilitas Kamar</button>
        </form>
      </div>

      <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
          <h3 style="margin:0;">Daftar Fasilitas Kamar</h3>
          <select id="roomFilter" class="form-control" style="width:auto; padding:5px 10px; font-size:0.9rem;" onchange="filterRooms()">
            <option value="all">Semua Kamar</option>
            @foreach($rooms as $r)
              <option value="{{ $r->id }}">{{ $r->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="table-responsive" style="max-height:450px;overflow-y:auto;">
          <table class="table" id="roomFacilitiesTable">
            <thead>
              <tr>
                <th>Kamar</th>
                <th>Nama Fasilitas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($roomFacilities as $rf)
                <tr class="rf-row" data-room-id="{{ $rf->room_id }}">
                  <td><strong style="color:#0F172A;font-size:0.85rem;">{{ $rf->room?->name }}</strong></td>
                  <td>
                    <i class="{{ $rf->icon ?? 'bi bi-check2-circle' }}" style="color:#C5A059;margin-right:6px;"></i>
                    <span>{{ $rf->facility_name ?? $rf->name }}</span>
                    <br><small style="color:#94a3b8;font-size:0.75rem;">({{ ucfirst($rf->category) }})</small>
                  </td>
                  <td>
                    <div style="display:flex; gap:6px;">
                      <button type="button" class="btn btn-navy" style="padding:5px 8px;" onclick="openEditRoomModal({{ $rf->id }}, '{{ addslashes($rf->facility_name) }}', '{{ addslashes($rf->icon) }}', '{{ $rf->category }}')">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <form action="{{ route('admin.facilities.destroyRoom', $rf->id) }}" method="POST" onsubmit="return confirm('Hapus fasilitas kamar ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:5px 8px;"><i class="bi bi-trash3-fill"></i></button>
                      </form>
                    </div>
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

  {{-- MODAL EDIT HOME FACILITY --}}
  <div id="editHomeModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:450px; padding:24px; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
      <h3 style="margin-top:0; color:#0F172A; margin-bottom:16px;">Edit Fasilitas Umum</h3>
      <form id="editHomeForm" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label>Nama Fasilitas</label>
          <input type="text" name="title" id="edit_home_title" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Deskripsi Singkat</label>
          <textarea name="description" id="edit_home_description" class="form-control" rows="3" required></textarea>
        </div>
        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
          <button type="button" class="btn btn-danger" onclick="closeEditHomeModal()">Batal</button>
          <button type="submit" class="btn btn-gold">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- MODAL EDIT ROOM FACILITY --}}
  <div id="editRoomModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:450px; padding:24px; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
      <h3 style="margin-top:0; color:#0F172A; margin-bottom:16px;">Edit Fasilitas Kamar</h3>
      <form id="editRoomForm" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label>Nama Fasilitas</label>
          <input type="text" name="name" id="edit_room_name" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Icon Class</label>
          <input type="text" name="icon" id="edit_room_icon" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <select name="category" id="edit_room_category" class="form-control">
            <option value="utama">Utama (Badge)</option>
            <option value="kamar">Fasilitas Kamar</option>
            <option value="bathroom">Kamar Mandi</option>
          </select>
        </div>
        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
          <button type="button" class="btn btn-danger" onclick="closeEditRoomModal()">Batal</button>
          <button type="submit" class="btn btn-navy">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function filterRooms() {
      const filter = document.getElementById('roomFilter').value;
      const rows = document.querySelectorAll('.rf-row');
      rows.forEach(row => {
        if (filter === 'all' || row.dataset.roomId === filter) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    function openEditHomeModal(id, title, description) {
      document.getElementById('edit_home_title').value = title;
      document.getElementById('edit_home_description').value = description;
      document.getElementById('editHomeForm').action = '/admin/facilities/home/' + id;
      document.getElementById('editHomeModal').style.display = 'flex';
    }

    function closeEditHomeModal() {
      document.getElementById('editHomeModal').style.display = 'none';
    }

    function openEditRoomModal(id, name, icon, category) {
      document.getElementById('edit_room_name').value = name;
      document.getElementById('edit_room_icon').value = icon;
      document.getElementById('edit_room_category').value = category;
      document.getElementById('editRoomForm').action = '/admin/facilities/room/' + id;
      document.getElementById('editRoomModal').style.display = 'flex';
    }

    function closeEditRoomModal() {
      document.getElementById('editRoomModal').style.display = 'none';
    }
  </script>

@endsection

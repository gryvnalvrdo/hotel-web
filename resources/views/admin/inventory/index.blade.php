@extends('layouts.admin')

@section('title', 'Laporan Stok & Ketersediaan Kamar')
@section('page-title', 'Room Inventory & Occupancy Dashboard')

@section('content')

<style>
  .inventory-header {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1.5px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
  }

  .stat-grid-4 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 28px;
  }

  .stat-box {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 20px;
    border: 1.5px solid var(--border);
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
  }
  .stat-box .icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
  }

  .room-inv-card {
    background: #FFFFFF;
    border-radius: 18px;
    border: 1.5px solid var(--border);
    margin-bottom: 28px;
    overflow: hidden;
    box-shadow: 0 6px 16px rgba(0,0,0,0.03);
  }

  .room-inv-header {
    background: #FDFBF7;
    border-bottom: 1.5px solid var(--border);
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
  }

  .room-inv-title {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .room-inv-title img {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--border);
  }
  .room-inv-title h3 {
    margin: 0 0 4px;
    font-family: "Playfair Display", serif;
    font-size: 1.35rem;
    color: var(--navy);
  }

  .stock-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .stock-avail {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #86efac;
  }
  .stock-warning {
    background: #fef9c3;
    color: #854d0e;
    border: 1px solid #fde047;
  }
  .stock-full {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fca5a5;
  }

  .progress-container {
    width: 100%;
    background: #E2E8F0;
    border-radius: 10px;
    height: 12px;
    overflow: hidden;
    margin-top: 8px;
  }
  .progress-bar {
    height: 100%;
    background: linear-gradient(135deg, #C5A059 0%, #9E7D3B 100%);
    border-radius: 10px;
    transition: width 0.5s ease;
  }
  .progress-bar.full {
    background: #DC2626;
  }

  .table-guests {
    width: 100%;
    border-collapse: collapse;
  }
  .table-guests th {
    background: #F8FAFC;
    color: #475569;
    font-weight: 600;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 24px;
    text-align: left;
    border-bottom: 1px solid var(--border);
  }
  .table-guests td {
    padding: 14px 24px;
    border-bottom: 1px solid #F1F5F9;
    color: #1E293B;
    font-size: 0.92rem;
  }
  .table-guests tr:last-child td {
    border-bottom: none;
  }
</style>

<div class="inventory-header">
  <div>
    <h2 style="font-family:'Playfair Display',serif;color:var(--navy);margin:0 0 4px;font-size:1.6rem;">Monitoring Stok & Daftar Tamu Real-Time</h2>
    <p style="color:#64748b;margin:0;font-size:0.95rem;">Pantau ketersediaan unit kamar dan tamu yang sedang menginap pada tanggal terpilih.</p>
  </div>

  <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
    <form action="{{ route('admin.inventory.index') }}" method="GET" style="display:flex;align-items:center;gap:12px;margin:0;">
      <label style="font-weight:600;color:var(--navy);font-size:0.9rem;"><i class="bi bi-calendar-event"></i> Pilih Tanggal:</label>
      <input type="date" name="date" value="{{ $date }}" class="form-control" style="padding:10px 14px;border-radius:10px;border:1.5px solid var(--border);font-weight:600;color:var(--navy);" onchange="this.form.submit()">
    </form>
    <button type="button" class="btn btn-gold" style="padding:10px 18px;border-radius:10px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="document.getElementById('quick-stock-box').style.display = document.getElementById('quick-stock-box').style.display === 'none' ? 'block' : 'none';">
      <i class="bi bi-gear-fill"></i> Atur Stok & Lantai Massal
    </button>
  </div>
</div>


<div id="quick-stock-box" style="display:none;background:#FFFFFF;border:1.5px solid #C5A059;border-radius:18px;padding:24px;margin-bottom:28px;box-shadow:0 10px 25px rgba(197,160,89,0.12);">
  <h3 style="margin:0 0 8px;color:var(--navy);font-family:'Playfair Display',serif;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-sliders" style="color:#C5A059;"></i> Pengaturan Stok & Lantai Seluruh Tipe Kamar
  </h3>
  <p style="font-size:0.88rem;color:#64748b;margin:0 0 18px;">Ubah total unit kamar dan lokasi lantai untuk masing-masing tipe kamar secara cepat di sini. Total keseluruhan akan menyesuaikan otomatis.</p>
  
  <form action="{{ route('admin.inventory.quickUpdate') }}" method="POST">
    @csrf
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;margin-bottom:20px;">
      @foreach($inventoryData as $inv)
        <div style="background:#F8FAFC;border:1px solid var(--border);border-radius:12px;padding:16px;">
          <strong style="color:var(--navy);font-size:1.02rem;display:block;margin-bottom:10px;">{{ $inv->room->name }}</strong>
          <div style="display:flex;gap:10px;align-items:center;margin-bottom:10px;">
            <label style="font-size:0.82rem;color:#475569;width:90px;">Total Stok:</label>
            <input type="number" name="stocks[{{ $inv->room->id }}]" value="{{ $inv->total_stock }}" min="1" class="form-control" style="padding:6px 10px;font-size:0.9rem;font-weight:700;color:var(--navy);" required>
          </div>
          <div style="display:flex;gap:10px;align-items:center;">
            <label style="font-size:0.82rem;color:#475569;width:90px;">Lokasi:</label>
            <input type="text" name="floors[{{ $inv->room->id }}]" value="{{ $inv->room->floor_location ?: 'Lantai 3 - 8' }}" class="form-control" style="padding:6px 10px;font-size:0.85rem;" placeholder="Contoh: Lantai 3 - 8">
          </div>
        </div>
      @endforeach
    </div>
    <div style="display:flex;justify-content:flex-end;gap:10px;">
      <button type="button" class="btn" style="background:#E2E8F0;color:#475569;font-weight:600;padding:8px 16px;border-radius:8px;" onclick="document.getElementById('quick-stock-box').style.display='none';">Batal</button>
      <button type="submit" class="btn btn-navy" style="padding:8px 20px;font-weight:700;border-radius:8px;"><i class="bi bi-save2-fill"></i> Simpan Perubahan Massal</button>
    </div>
  </form>
</div>


<div class="stat-grid-4">
  <div class="stat-box">
    <div class="icon" style="background:#EEF2FF;color:#4F46E5;"><i class="bi bi-building"></i></div>
    <div>
      <span style="font-size:0.82rem;color:#64748b;display:block;">TOTAL UNIT KAMAR</span>
      <strong style="font-size:1.5rem;color:var(--navy);">{{ $totalHotelStock }} Unit</strong>
    </div>
  </div>

  <div class="stat-box">
    <div class="icon" style="background:#FEF3C7;color:#D97706;"><i class="bi bi-key-fill"></i></div>
    <div>
      <span style="font-size:0.82rem;color:#64748b;display:block;">TERISI / DIPESAN (BOOKED)</span>
      <strong style="font-size:1.5rem;color:#D97706;">{{ $totalHotelBooked }} Unit</strong>
    </div>
  </div>

  <div class="stat-box">
    <div class="icon" style="background:#DCFCE7;color:#16A34A;"><i class="bi bi-door-open-fill"></i></div>
    <div>
      <span style="font-size:0.82rem;color:#64748b;display:block;">TERSEDIA (AVAILABLE)</span>
      <strong style="font-size:1.5rem;color:#16A34A;">{{ max(0, $totalHotelStock - $totalHotelBooked) }} Unit</strong>
    </div>
  </div>

  <div class="stat-box">
    <div class="icon" style="background:#F3E8FF;color:#9333EA;"><i class="bi bi-pie-chart-fill"></i></div>
    <div>
      <span style="font-size:0.82rem;color:#64748b;display:block;">TINGKAT HUNIAN (OCCUPANCY)</span>
      <strong style="font-size:1.5rem;color:#9333EA;">{{ $overallOccupancy }}%</strong>
    </div>
  </div>
</div>


@foreach($inventoryData as $inv)
  <div class="room-inv-card">
    <div class="room-inv-header">
      <div class="room-inv-title">
        <img src="{{ asset($inv->room->thumbnail()?->image_path ?? 'images/slider/slider1.jpg') }}" alt="{{ $inv->room->name }}">
        <div>
          <h3>{{ $inv->room->name }} <span style="font-size:0.85rem;font-weight:600;background:#f1f5f9;color:#334155;padding:4px 12px;border-radius:12px;margin-left:8px;"><i class="bi bi-geo-alt-fill" style="color:#C5A059;"></i> {{ $inv->room->floor_location ?: 'Lantai 3 - 8' }}</span></h3>
          <div style="font-size:0.88rem;color:#64748b;margin-top:4px;">
            Total Stok: <strong style="color:var(--navy);">{{ $inv->total_stock }} Kamar</strong> · 
            Terisi: <strong style="color:#D97706;">{{ $inv->booked_stock }} Kamar</strong> · 
            Tersedia: <strong style="color:#16A34A;">{{ $inv->available_stock }} Kamar</strong>
          </div>
          <div class="progress-container" style="width:240px;">
            <div class="progress-bar {{ $inv->available_stock <= 0 ? 'full' : '' }}" style="width: {{ $inv->occupancy_rate }}%;"></div>
          </div>
        </div>
      </div>

      <div>
        @if($inv->available_stock <= 0)
          <span class="stock-badge stock-full"><i class="bi bi-x-circle-fill"></i> FULLY BOOKED (0 Tersedia)</span>
        @elseif($inv->available_stock <= 3)
          <span class="stock-badge stock-warning"><i class="bi bi-exclamation-triangle-fill"></i> Sisa {{ $inv->available_stock }} Kamar!</span>
        @else
          <span class="stock-badge stock-avail"><i class="bi bi-check-circle-fill"></i> Tersedia (Sisa {{ $inv->available_stock }} Kamar)</span>
        @endif
      </div>
    </div>

    @if($inv->active_bookings->count() > 0)
      <table class="table-guests">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Tamu & Kontak</th>
            <th>Jadwal Check-in s/d Check-out</th>
            <th>Durasi & Qty</th>
            <th>Status Pembayaran</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($inv->active_bookings as $index => $b)
            @php
              $itemQty = 1;
              if (!empty($b->room_details)) {
                $details = json_decode($b->room_details, true);
                if (is_array($details)) {
                  foreach ($details as $d) {
                    if (($d['id'] ?? null) == $inv->room->id) {
                      $itemQty = (int)($d['qty'] ?? 1);
                    }
                  }
                }
              } else {
                $itemQty = (int)($b->room_count ?: 1);
              }
            @endphp
            <tr>
              <td style="width:50px;color:#64748b;">{{ $index + 1 }}</td>
              <td>
                <strong style="color:var(--navy);display:block;">{{ $b->name }}</strong>
                <small style="color:#64748b;"><i class="bi bi-envelope"></i> {{ $b->email }} | <i class="bi bi-phone"></i> {{ $b->phone }}</small>
              </td>
              <td>
                <strong style="color:#15803d;">{{ \Carbon\Carbon::parse($b->checkin)->format('d M Y') }}</strong> 
                <span style="color:#94a3b8;">→</span> 
                <strong style="color:#b91c1c;">{{ \Carbon\Carbon::parse($b->checkout)->format('d M Y') }}</strong>
              </td>
              <td>
                <strong style="color:var(--navy);">{{ $itemQty }} Kamar</strong> 
                <span style="color:#64748b;">({{ $b->nights }} Malam)</span>
              </td>
              <td>
                @if($b->payment_status === 'paid')
                  <span style="background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:12px;font-weight:700;font-size:0.78rem;">✓ LUNAS</span>
                @elseif($b->payment_status === 'pay_at_hotel')
                  <span style="background:#fef9c3;color:#854d0e;padding:4px 10px;border-radius:12px;font-weight:700;font-size:0.78rem;">🏨 PAY AT HOTEL</span>
                @else
                  <span style="background:#f1f5f9;color:#475569;padding:4px 10px;border-radius:12px;font-weight:700;font-size:0.78rem;">⏳ UNPAID</span>
                @endif
              </td>
              <td>
                <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn" style="background:#0F172A;color:#FFF;padding:6px 12px;border-radius:8px;font-size:0.82rem;text-decoration:none;">
                  <i class="bi bi-eye"></i> Detail
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <div style="padding:30px;text-align:center;color:#64748b;">
        <i class="bi bi-calendar2-x" style="font-size:2rem;display:block;margin-bottom:8px;color:#cbd5e1;"></i>
        Belum ada pesanan kamar untuk tipe ini pada tanggal <strong>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</strong>.
      </div>
    @endif
  </div>
@endforeach

@endsection

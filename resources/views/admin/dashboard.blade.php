@extends('layouts.admin')

@section('title', 'Dashboard Overview — Admin Web Hotel')
@section('page-title', 'Dashboard Executive Overview')

@push('styles')
  <style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px; }
    .stat-card { background: #FFFFFF; padding: 24px; border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 8px 25px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 20px; }
    .stat-icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
    .stat-icon.gold { background: rgba(197, 160, 89, 0.15); color: #C5A059; }
    .stat-icon.navy { background: rgba(15, 23, 42, 0.1); color: #0F172A; }
    .stat-icon.green { background: rgba(34, 197, 94, 0.15); color: #16a34a; }
    .stat-icon.blue { background: rgba(59, 130, 246, 0.15); color: #2563eb; }
    .stat-info h4 { font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
    .stat-info h2 { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #0F172A; }
    @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } }
  </style>
@endpush

@section('content')

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon gold"><i class="bi bi-calendar-check-fill"></i></div>
      <div class="stat-info">
        <h4>Total Reservasi</h4>
        <h2>{{ $totalBookings }} Pesanan</h2>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="bi bi-wallet2"></i></div>
      <div class="stat-info">
        <h4>Estimasi Pendapatan</h4>
        <h2>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon navy"><i class="bi bi-door-closed-fill"></i></div>
      <div class="stat-info">
        <h4>Katalog Kamar</h4>
        <h2>{{ $totalRooms }} Tipe Kamar</h2>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon blue"><i class="bi bi-stars"></i></div>
      <div class="stat-info">
        <h4>Total Fasilitas</h4>
        <h2>{{ $totalFacilities }} Item</h2>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:32px;">
    <div class="card-header">
      <h3>📈 Analitik Pendapatan Tahun {{ date('Y') }}</h3>
    </div>
    <div style="padding: 20px; height: 350px;">
      <canvas id="revenueChart"></canvas>
    </div>
  </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('revenueChart').getContext('2d');
  
  // Custom gradient for line chart
  const gradient = ctx.createLinearGradient(0, 0, 0, 350);
  gradient.addColorStop(0, 'rgba(197, 160, 89, 0.4)');
  gradient.addColorStop(1, 'rgba(197, 160, 89, 0.01)');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: @json($monthlyLabels),
      datasets: [{
        label: 'Pendapatan (Rp)',
        data: @json($monthlyRevenue),
        borderColor: '#C5A059',
        backgroundColor: gradient,
        borderWidth: 3,
        pointBackgroundColor: '#0F172A',
        pointBorderColor: '#C5A059',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
        fill: true,
        tension: 0.4 // Smooth curves
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0F172A',
          padding: 12,
          titleFont: { family: 'Inter', size: 13 },
          bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
          callbacks: {
            label: function(context) {
              let val = context.raw || 0;
              return ' Rp ' + val.toLocaleString('id-ID');
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { borderDash: [4, 4], color: '#e2e8f0' },
          ticks: {
            callback: function(value) {
              if (value >= 1000000) return 'Rp ' + (value/1000000) + ' Jt';
              if (value >= 1000) return 'Rp ' + (value/1000) + ' Rb';
              return value;
            }
          }
        },
        x: {
          grid: { display: false }
        }
      }
    }
  });
</script>
@endpush

  <div class="card">
    <div class="card-header">
      <h3>Daftar Reservasi Terbaru</h3>
      <a href="{{ route('admin.bookings.index') }}" class="btn btn-gold">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Nama Tamu</th>
            <th>Tipe Kamar</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Total Tarif</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentBookings as $b)
            <tr>
              <td><strong style="font-family:monospace;color:#0F172A;">{{ $b->midtrans_order_id }}</strong></td>
              <td>{{ $b->name }}</td>
              <td>{{ $b->room?->name ?? 'Kamar dihapus' }} ({{ $b->room_count }} kmr)</td>
              <td>{{ \Carbon\Carbon::parse($b->checkin)->format('d M Y') }}</td>
              <td>{{ \Carbon\Carbon::parse($b->checkout)->format('d M Y') }}</td>
              <td><strong style="color:#9E7D3B;">Rp {{ number_format($b->total_price, 0, ',', '.') }}</strong></td>
              <td>
                @if($b->payment_status === 'paid')
                  <span style="background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:12px;font-size:0.75rem;font-weight:700;">✓ Lunas (Paid)</span>
                @elseif($b->payment_status === 'pay_at_hotel')
                  <span style="background:#fef9c3;color:#854d0e;padding:4px 10px;border-radius:12px;font-size:0.75rem;font-weight:700;">🏨 Pay at Hotel</span>
                @elseif($b->payment_status === 'cancelled')
                  <span style="background:#fee2e2;color:#b91c1c;padding:4px 10px;border-radius:12px;font-size:0.75rem;font-weight:700;">✕ Batal</span>
                @else
                  <span style="background:#f1f5f9;color:#475569;padding:4px 10px;border-radius:12px;font-size:0.75rem;font-weight:700;">⏳ Belum Bayar</span>
                @endif
              </td>
              <td>
                <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-navy" style="padding:6px 12px;font-size:0.8rem;"><i class="bi bi-eye"></i> Detail</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align:center;padding:30px;color:#64748b;">Belum ada data reservasi yang masuk.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3>Katalog Harga & Kapasitas Kamar Aktif</h3>
      <a href="{{ route('admin.rooms.index') }}" class="btn btn-gold"><i class="bi bi-gear-fill"></i> Kelola Kamar</a>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nama Tipe Kamar</th>
            <th>Tarif / Malam</th>
            <th>Kapasitas Tamu</th>
            <th>Aksi Cepat</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rooms as $r)
            <tr>
              <td>
                <img src="{{ asset($r->images->first()?->file_path ?? 'images/slider/slider1.jpg') }}" style="width:70px;height:50px;object-fit:cover;border-radius:8px;" alt="{{ $r->name }}">
              </td>
              <td><strong style="font-size:1.05rem;color:#0F172A;">{{ $r->name }}</strong></td>
              <td><span style="color:#9E7D3B;font-weight:700;font-size:1.05rem;">Rp {{ number_format($r->price, 0, ',', '.') }}</span> / mlm</td>
              <td><span style="background:#F8F6F0;padding:4px 12px;border-radius:10px;font-weight:600;"><i class="bi bi-person-fill" style="color:#C5A059;"></i> {{ $r->capacity }} Tamu</span></td>
              <td>
                <a href="{{ route('admin.rooms.edit', $r->id) }}" class="btn btn-navy" style="padding:6px 14px;font-size:0.82rem;"><i class="bi bi-pencil-square"></i> Edit Harga & Data</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@endsection

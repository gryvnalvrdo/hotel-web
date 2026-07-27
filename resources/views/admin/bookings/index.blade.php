@extends('layouts.admin')

@section('title', 'Daftar Reservasi & Bookings — Admin Grand Lumina')
@section('page-title', 'Manajemen Daftar Reservasi Tamu')

@section('content')

  <div class="card">
    <div class="card-header">
      <h3>Riwayat & Reservasi Masuk</h3>
      
      {{-- Filter Status --}}
      <form action="{{ route('admin.bookings.index') }}" method="GET" style="display:flex;gap:10px;align-items:center;">
        <select name="status" class="form-control" style="width:200px;padding:8px 12px;font-size:0.88rem;" onchange="this.form.submit()">
          <option value="">-- Semua Status --</option>
          <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
          <option value="pay_at_hotel" {{ request('status') === 'pay_at_hotel' ? 'selected' : '' }}>Pay at Hotel</option>
          <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
          <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Batal (Cancelled)</option>
        </select>
        @if(request('status'))
          <a href="{{ route('admin.bookings.index') }}" class="btn btn-navy" style="padding:8px 12px;font-size:0.8rem;">Reset</a>
        @endif
      </form>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Order Ref ID</th>
            <th>Data Tamu</th>
            <th>Tipe Kamar</th>
            <th>Check-in & Out</th>
            <th>Total Tagihan</th>
            <th>Status Pembayaran</th>
            <th>Ubah Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $b)
            <tr>
              <td><strong style="font-family:monospace;color:#0F172A;font-size:1.05rem;">{{ $b->midtrans_order_id }}</strong></td>
              <td>
                <strong style="color:#0F172A;display:block;">{{ $b->name }}</strong>
                <span style="font-size:0.8rem;color:#64748b;"><i class="bi bi-envelope"></i> {{ $b->email }}</span><br>
                <span style="font-size:0.8rem;color:#64748b;"><i class="bi bi-telephone"></i> {{ $b->phone }}</span>
              </td>
              <td>
                <strong style="color:#1C1917;">{{ $b->room?->name ?? 'Kamar tidak ditemukan' }}</strong>
                <span style="font-size:0.8rem;color:#64748b;display:block;">{{ $b->room_count }} Kamar · {{ $b->guests }} Tamu</span>
              </td>
              <td>
                <span style="font-weight:600;color:#0F172A;">In: {{ \Carbon\Carbon::parse($b->checkin)->format('d/m/Y') }}</span><br>
                <span style="font-weight:600;color:#64748b;">Out: {{ \Carbon\Carbon::parse($b->checkout)->format('d/m/Y') }}</span>
                @if($b->payment_status !== 'cancelled' && $b->checkout <= now()->format('Y-m-d'))
                  <br><span style="background:#e0f2fe;color:#0369a1;padding:3px 8px;border-radius:10px;font-size:0.72rem;font-weight:700;display:inline-block;margin-top:4px;"><i class="bi bi-check-all"></i> Selesai (Stok Kembali)</span>
                @elseif($b->payment_status !== 'cancelled' && $b->checkin <= now()->format('Y-m-d') && $b->checkout > now()->format('Y-m-d'))
                  <br><span style="background:#fef9c3;color:#854d0e;padding:3px 8px;border-radius:10px;font-size:0.72rem;font-weight:700;display:inline-block;margin-top:4px;"><i class="bi bi-door-open-fill"></i> Sedang Menginap</span>
                @endif
              </td>
              <td>
                <strong style="color:#9E7D3B;font-size:1.1rem;">Rp {{ number_format($b->total_price, 0, ',', '.') }}</strong>
              </td>
              <td>
                @if($b->payment_status === 'paid')
                  <span style="background:#dcfce7;color:#15803d;padding:5px 12px;border-radius:20px;font-size:0.78rem;font-weight:700;">✓ Lunas</span>
                @elseif($b->payment_status === 'pay_at_hotel')
                  <span style="background:#fef9c3;color:#854d0e;padding:5px 12px;border-radius:20px;font-size:0.78rem;font-weight:700;">🏨 Bayar di Hotel</span>
                @elseif($b->payment_status === 'cancelled')
                  <span style="background:#fee2e2;color:#b91c1c;padding:5px 12px;border-radius:20px;font-size:0.78rem;font-weight:700;">✕ Batal</span>
                @else
                  <span style="background:#f1f5f9;color:#475569;padding:5px 12px;border-radius:20px;font-size:0.78rem;font-weight:700;">⏳ Belum Bayar</span>
                @endif
              </td>
              <td>
                <form action="{{ route('admin.bookings.updateStatus', $b->id) }}" method="POST" style="display:flex;gap:4px;">
                  @csrf
                  @method('PUT')
                  <select name="payment_status" class="form-control" style="padding:4px 8px;font-size:0.8rem;width:125px;" onchange="this.form.submit()">
                    <option value="paid" {{ $b->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="pay_at_hotel" {{ $b->payment_status === 'pay_at_hotel' ? 'selected' : '' }}>Pay at Hotel</option>
                    <option value="unpaid" {{ $b->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="cancelled" {{ $b->payment_status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                  </select>
                </form>
              </td>
              <td>
                <div style="display:flex;gap:6px;">
                  <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-navy" style="padding:6px 10px;" title="Lihat Detail"><i class="bi bi-eye-fill"></i></a>
                  <form action="{{ route('admin.bookings.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat reservasi ini secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding:6px 10px;" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align:center;padding:40px;color:#64748b;">Tidak ada riwayat pesanan/reservasi yang sesuai dengan filter.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="margin-top:20px;">
      {{ $bookings->links() }}
    </div>
  </div>

@endsection

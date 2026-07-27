@extends('layouts.admin')

@section('title', 'Detail Reservasi #{{ $booking->midtrans_order_id }} — Admin Web Hotel')
@section('page-title', 'Detail Lembar Reservasi Tamu')

@section('content')

  <div style="display:grid;grid-template-columns:2fr 1fr;gap:28px;">
    
    
    <div class="card">
      <div class="card-header">
        <h3>e-Voucher & Lembar Reservasi #{{ $booking->midtrans_order_id }}</h3>
        <a href="{{ route('booking.invoice', $booking->id) }}" target="_blank" class="btn btn-gold" style="font-size:0.85rem;"><i class="bi bi-file-earmark-pdf-fill"></i> Cetak / Simpan PDF</a>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;background:#F8F6F0;padding:20px;border-radius:16px;">
        <div>
          <span style="font-size:0.82rem;color:#64748b;text-transform:uppercase;">Nama Pemesan / Tamu:</span>
          <strong style="display:block;font-size:1.15rem;color:#0F172A;margin-top:4px;">{{ $booking->name }}</strong>
        </div>
        <div>
          <span style="font-size:0.82rem;color:#64748b;text-transform:uppercase;">Kontak Telepon / WA:</span>
          <strong style="display:block;font-size:1.1rem;color:#0F172A;margin-top:4px;"><i class="bi bi-whatsapp" style="color:#22c55e;"></i> {{ $booking->phone }}</strong>
        </div>
        <div>
          <span style="font-size:0.82rem;color:#64748b;text-transform:uppercase;">Alamat Email:</span>
          <strong style="display:block;font-size:1.05rem;color:#0F172A;margin-top:4px;">{{ $booking->email }}</strong>
        </div>
        <div>
          <span style="font-size:0.82rem;color:#64748b;text-transform:uppercase;">Waktu Reservasi Dibuat:</span>
          <strong style="display:block;font-size:0.95rem;color:#0F172A;margin-top:4px;">{{ $booking->created_at->format('d M Y, H:i') }} WITA</strong>
        </div>
      </div>

      <h4 style="font-family:'Playfair Display',serif;color:#0F172A;font-size:1.25rem;margin-bottom:14px;border-bottom:1px solid var(--border);padding-bottom:10px;">Rincian Kamar & Waktu Menginap</h4>
      <table class="table" style="margin-bottom:24px;">
        <tr>
          <td style="width:200px;color:#64748b;">Tipe Kamar Terpilih:</td>
          <td>
            @if($booking->room_details)
              @php $details = json_decode($booking->room_details, true); @endphp
              @if(is_array($details) && count($details) > 0)
                <ul style="margin:0;padding-left:18px;">
                  @foreach($details as $item)
                    <li><strong style="font-size:1.05rem;color:#0F172A;">{{ $item['name'] ?? 'Kamar' }}</strong> <span style="color:#C5A059;font-weight:bold;">({{ $item['qty'] ?? 1 }} Kamar)</span></li>
                  @endforeach
                </ul>
              @else
                <strong style="font-size:1.1rem;color:#0F172A;">{{ $booking->room?->name ?? 'Kamar Tidak Ditemukan' }}</strong>
              @endif
            @else
              <strong style="font-size:1.1rem;color:#0F172A;">{{ $booking->room?->name ?? 'Kamar Tidak Ditemukan' }}</strong>
            @endif
          </td>
        </tr>
        <tr>
          <td style="color:#64748b;">Jumlah Kamar Dipesan:</td>
          <td><strong style="color:#0F172A;">{{ $booking->room_count }} Kamar</strong></td>
        </tr>
        <tr>
          <td style="color:#64748b;">Jumlah Tamu Terdaftar:</td>
          <td><strong style="color:#0F172A;">{{ $booking->guests }} Tamu</strong></td>
        </tr>
        <tr>
          <td style="color:#64748b;">Jadwal Check-in:</td>
          <td><strong style="color:#15803d;font-size:1.05rem;">{{ \Carbon\Carbon::parse($booking->checkin)->format('l, d F Y') }}</strong> (Mulai 14:00 WITA)</td>
        </tr>
        <tr>
          <td style="color:#64748b;">Jadwal Check-out:</td>
          <td><strong style="color:#b91c1c;font-size:1.05rem;">{{ \Carbon\Carbon::parse($booking->checkout)->format('l, d F Y') }}</strong> (Maks 12:00 WITA)</td>
        </tr>
        <tr>
          <td style="color:#64748b;">Durasi Menginap:</td>
          <td><strong style="color:#0F172A;">{{ $booking->nights }} Malam</strong></td>
        </tr>
        <tr>
          <td style="color:#64748b;">Catatan Khusus Tamu:</td>
          <td><em style="color:#475569;">"{{ $booking->special_request ?: 'Tidak ada catatan tambahan.' }}"</em></td>
        </tr>
        @if($booking->discount_amount > 0)
        <tr>
          <td style="color:#64748b;">Promo Diterapkan:</td>
          <td><strong style="color:#b91c1c;">{{ $booking->promo_code }} (Diskon: Rp {{ number_format($booking->discount_amount, 0, ',', '.') }})</strong></td>
        </tr>
        @endif
      </table>

      <div style="display:flex;justify-content:space-between;align-items:center;background:#0F172A;color:#FFF;padding:20px 24px;border-radius:16px;">
        <div>
          <span style="font-size:0.85rem;color:#94a3b8;display:block;">TOTAL TAGIHAN RESERVASI:</span>
          <span style="font-size:0.8rem;color:#cbd5e1;">Sudah termasuk pajak pemerintah 10% & pelayanan 5%</span>
        </div>
        <div style="text-align:right;">
          <strong style="font-family:'Playfair Display',serif;font-size:1.8rem;color:#D4AF37;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
        </div>
      </div>
    </div>

    
    <div>
      <div class="card" style="margin-bottom:24px;">
        <div class="card-header">
          <h3>Status Pembayaran</h3>
        </div>
        <div style="text-align:center;margin-bottom:20px;">
          @if($booking->payment_status === 'paid')
            <div style="background:#dcfce7;color:#15803d;padding:16px;border-radius:14px;font-weight:700;font-size:1.1rem;border:1px solid #86efac;">
              <i class="bi bi-check-circle-fill" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
              PEMBAYARAN LUNAS (PAID)
            </div>
          @elseif($booking->payment_status === 'pay_at_hotel')
            <div style="background:#fef9c3;color:#854d0e;padding:16px;border-radius:14px;font-weight:700;font-size:1.1rem;border:1px solid #fde047;">
              <i class="bi bi-building-check" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
              BAYAR DI HOTEL (PAY AT HOTEL)
            </div>
          @elseif($booking->payment_status === 'cancelled')
            <div style="background:#fee2e2;color:#b91c1c;padding:16px;border-radius:14px;font-weight:700;font-size:1.1rem;border:1px solid #fca5a5;">
              <i class="bi bi-x-circle-fill" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
              RESERVASI DIBATALKAN
            </div>
          @else
            <div style="background:#f1f5f9;color:#475569;padding:16px;border-radius:14px;font-weight:700;font-size:1.1rem;border:1px solid #cbd5e1;">
              <i class="bi bi-clock-history" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
              BELUM DIBAYAR (UNPAID)
            </div>
          @endif
        </div>

        <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label>Ubah Status Secara Manual:</label>
            <select name="payment_status" class="form-control" required>
              <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>✓ Lunas (Paid)</option>
              <option value="pay_at_hotel" {{ $booking->payment_status === 'pay_at_hotel' ? 'selected' : '' }}>🏨 Bayar di Hotel (Pay at Hotel)</option>
              <option value="unpaid" {{ $booking->payment_status === 'unpaid' ? 'selected' : '' }}>⏳ Belum Bayar (Unpaid)</option>
              <option value="cancelled" {{ $booking->payment_status === 'cancelled' ? 'selected' : '' }}>✕ Batal (Cancelled)</option>
            </select>
          </div>
          <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;"><i class="bi bi-check2-circle"></i> Simpan Status Baru</button>
        </form>
      </div>

      <div class="card">
        <div class="card-header">
          <h3>Tindakan Lainnya</h3>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-navy" style="width:100%;justify-content:center;margin-bottom:12px;"><i class="bi bi-list-check"></i> Kembali ke Daftar</a>
        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus data reservasi ini secara permanen dari database?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;"><i class="bi bi-trash3-fill"></i> Hapus Data Reservasi</button>
        </form>
      </div>
    </div>

  </div>

@endsection

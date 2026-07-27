@extends('layouts.app')

@section('title', 'Web Hotel — Pembayaran & Konfirmasi')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/booking.css') }}" />
  <style>
    .payment-page {
      min-height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 130px 20px 80px;
      position: relative;
      background: url('{{ asset("images/slider/slider1.jpg") }}') center/cover no-repeat fixed;
    }
    .payment-page::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(0,16,36,0.85) 0%, rgba(0,10,24,0.95) 100%);
      backdrop-filter: blur(8px);
    }
    .payment-card {
      position: relative;
      z-index: 2;
      background: linear-gradient(145deg, rgba(13, 38, 68, 0.92) 0%, rgba(5, 20, 38, 0.96) 100%);
      border: 1px solid rgba(212,175,55,0.35);
      border-radius: 24px;
      padding: 44px 38px;
      max-width: 620px;
      width: 100%;
      text-align: center;
      box-shadow: 0 24px 80px rgba(0,0,0,0.7), 0 0 25px rgba(212,175,55,0.15);
      animation: fadeInUp 0.7s ease both;
    }
    .payment-card h2 {
      font-family: "Playfair Display", serif;
      color: var(--gold-light, #f7c948);
      font-size: 2rem;
      margin: 10px 0 8px;
      letter-spacing: 0.5px;
    }
    .payment-card .subtitle {
      color: #aab8cc;
      margin-bottom: 28px;
      font-size: 0.95rem;
      line-height: 1.5;
    }
    .booking-summary-box {
      background: rgba(0, 15, 32, 0.65);
      border: 1px solid rgba(212,175,55,0.22);
      border-radius: 16px;
      padding: 22px;
      text-align: left;
      margin-bottom: 28px;
    }
    .booking-summary-box p {
      margin: 10px 0;
      color: #f0f4f8;
      font-size: 0.95rem;
      display: flex;
      justify-content: space-between;
      border-bottom: 1px dashed rgba(255,255,255,0.08);
      padding-bottom: 8px;
    }
    .booking-summary-box p:last-child { border-bottom: none; }
    .booking-summary-box p strong { color: #aab8cc; font-weight: 500; }
    .total-price {
      border-top: 1px solid rgba(212,175,55,0.35);
      margin-top: 14px;
      padding-top: 14px;
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--gold-light, #f7c948);
      display: flex;
      justify-content: space-between;
      font-family: "Playfair Display", serif;
    }
    .pay-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, var(--gold, #d4af37), var(--gold-light, #f7c948));
      color: #001024;
      font-weight: 800;
      font-size: 1.1rem;
      border: none;
      border-radius: 14px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 10px 30px rgba(212,175,55,0.3);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .pay-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 40px rgba(212,175,55,0.5);
      background: linear-gradient(135deg, #ffe082, var(--gold, #d4af37));
    }
    .pay-methods-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 8px;
      margin-top: 20px;
    }
    .pay-method-item {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 8px;
      padding: 8px 4px;
      font-size: 0.75rem;
      color: #fff;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
    }
    .order-id {
      font-size: 0.8rem;
      color: #778899;
      margin-top: 20px;
      background: rgba(0,0,0,0.3);
      padding: 6px 14px;
      border-radius: 20px;
      display: inline-block;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(24px); }
      to { opacity: 1; transform: none; }
    }
    @media (max-width: 600px) {
      .pay-methods-grid { grid-template-columns: repeat(2, 1fr); }
      .payment-card { padding: 30px 20px; }
    }
  </style>
@endpush

@section('content')

<div class="payment-page">
  <div class="payment-card">
    <div style="width:70px;height:70px;line-height:70px;background:rgba(212,175,55,0.15);border:1px solid var(--gold);border-radius:50%;margin:0 auto 12px;font-size:1.8rem;color:var(--gold-light);">
      <i class="bi bi-shield-lock-fill"></i>
    </div>
    <h2>Konfirmasi & Pembayaran</h2>
    <p class="subtitle">Reservasi kamar Anda telah diaktifkan. Silakan selesaikan pembayaran untuk mendapatkan e-Voucher resmi.</p>

    {{-- Booking Summary --}}
    <div class="booking-summary-box">
      <p><strong>Nama Tamu:</strong> <span>{{ $booking->name }}</span></p>
      <p><strong>Tipe Kamar:</strong> <span>{{ $booking->room?->name }} ({{ $booking->room_count }} Kamar)</span></p>
      <p><strong>Jadwal Check-in:</strong> <span>{{ \Carbon\Carbon::parse($booking->checkin)->format('d M Y') }} (14:00 WIB)</span></p>
      <p><strong>Jadwal Check-out:</strong> <span>{{ \Carbon\Carbon::parse($booking->checkout)->format('d M Y') }} (12:00 WIB)</span></p>
      <p><strong>Durasi Menginap:</strong> <span>{{ $booking->nights }} Malam · {{ $booking->guests }} Tamu</span></p>
      <div class="total-price">
        <span>Total Pembayaran</span>
        <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
      </div>
    </div>

    {{-- Tombol Bayar Midtrans --}}
    @if($booking->midtrans_token)
      <button class="pay-btn" id="pay-btn" onclick="payWithMidtrans()">
        <i class="bi bi-credit-card-2-front-fill"></i> Pilih Saluran Pembayaran
      </button>

      <div style="margin-top:18px;font-size:0.82rem;color:#aab8cc;">
        Mendukung berbagai metode pembayaran otomatis melalui Midtrans:
      </div>
      <div class="pay-methods-grid">
        <div class="pay-method-item"><i class="bi bi-bank"></i> BCA VA</div>
        <div class="pay-method-item"><i class="bi bi-bank"></i> Mandiri VA</div>
        <div class="pay-method-item"><i class="bi bi-bank"></i> BNI VA</div>
        <div class="pay-method-item"><i class="bi bi-bank"></i> BRI VA</div>
        <div class="pay-method-item"><i class="bi bi-wallet2"></i> GoPay</div>
        <div class="pay-method-item"><i class="bi bi-wallet2"></i> OVO</div>
        <div class="pay-method-item"><i class="bi bi-wallet2"></i> DANA</div>
        <div class="pay-method-item"><i class="bi bi-qr-code"></i> QRIS</div>
      </div>
    @else
      {{-- Fallback jika Midtrans belum dikonfigurasi --}}
      <div style="background:rgba(247,201,72,0.12); border:1px solid rgba(247,201,72,0.35); border-radius:12px; padding:16px; margin-bottom:24px; color:#f7c948; font-size:0.9rem; display:flex; align-items:center; gap:10px; text-align:left;">
        <i class="bi bi-info-circle-fill" style="font-size:1.3rem;"></i>
        <div>
          <strong>Midtrans Sandbox / Simulasi Aktif</strong><br>
          <span style="font-size:0.82rem;opacity:0.9;">API Key belum diisi. Anda dapat melanjutkan konfirmasi reservasi dengan menekan tombol simulasi di bawah.</span>
        </div>
      </div>
      <form action="{{ route('booking.simulate-pay', $booking->id) }}" method="POST">
        @csrf
        <button type="submit" class="pay-btn">
          <i class="bi bi-check-circle-fill"></i> Konfirmasi Pembayaran Berhasil
        </button>
      </form>
    @endif

    <div>
      <span class="order-id">Order ID: {{ $booking->midtrans_order_id }}</span>
    </div>
  </div>
</div>

@endsection

@push('scripts')
@if($booking->midtrans_token)
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
  function payWithMidtrans() {
    snap.pay('{{ $booking->midtrans_token }}', {
      onSuccess: function(result) {
        window.location.href = '{{ route("booking.success", ["id" => $booking->id]) }}';
      },
      onPending: function(result) {
        alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
      },
      onError: function(result) {
        alert('Pembayaran gagal. Silakan coba lagi.');
      },
      onClose: function() {
        // User tutup popup tanpa bayar
      }
    });
  }
</script>
@endif
@endpush

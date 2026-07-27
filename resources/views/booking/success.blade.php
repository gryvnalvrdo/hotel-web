@extends('layouts.app')

@section('title', 'Web Hotel — Reservasi Berhasil!')
@section('description', 'e-Voucher reservasi resmi Web Hotel telah terbit.')

@push('styles')
  <style>
    .success-page {
      min-height: 85vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 110px 20px 80px;
      background: linear-gradient(180deg, #f8fafc 0%, #edf2f7 100%);
    }

    .voucher-card {
      width: 100%;
      max-width: 780px;
      background: #ffffff;
      border-radius: 24px;
      padding: 45px 40px;
      box-shadow: 0 25px 70px rgba(0, 33, 71, 0.12);
      border-top: 8px solid #002147;
      border-bottom: 8px solid #d4af37;
      text-align: center;
      position: relative;
      animation: zoomIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .icon-circle {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, #002147, #003366);
      color: #d4af37;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
      margin: 0 auto 24px;
      box-shadow: 0 10px 25px rgba(0, 33, 71, 0.25);
      border: 3px solid #d4af37;
    }

    .voucher-card h1 {
      font-family: "Playfair Display", serif;
      font-size: 2.3rem;
      color: #002147;
      margin: 0 0 10px;
      font-weight: 700;
    }

    .subtitle {
      color: #64748b;
      font-size: 1.05rem;
      margin-bottom: 24px;
      line-height: 1.6;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    .email-notice-box {
      background: #f0fdf4;
      border: 1.5px solid #bbf7d0;
      color: #166534;
      border-radius: 14px;
      padding: 16px 20px;
      margin: 0 auto 30px;
      max-width: 620px;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 12px;
      text-align: left;
      box-shadow: 0 4px 12px rgba(22, 101, 52, 0.05);
    }

    .email-notice-box i {
      font-size: 1.5rem;
      color: #15803d;
      flex-shrink: 0;
    }

    .voucher-details {
      background: #f8fafc;
      border: 1.5px dashed #cbd5e1;
      border-radius: 16px;
      padding: 28px 30px;
      margin: 28px 0;
      text-align: left;
    }

    .voucher-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #e2e8f0;
      font-size: 0.98rem;
    }

    .voucher-row:last-child {
      border-bottom: none;
    }

    .voucher-row strong {
      color: #64748b;
      font-weight: 600;
    }

    .voucher-row span {
      color: #0f172a;
      font-weight: 700;
      text-align: right;
    }

    .status-badge {
      display: inline-block;
      padding: 5px 14px;
      border-radius: 20px;
      font-size: 0.82rem;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    .status-paid { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .status-hotel { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }

    .btn-cta-gold {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #002147;
      color: #ffffff;
      padding: 14px 28px;
      border-radius: 12px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      box-shadow: 0 8px 20px rgba(0, 33, 71, 0.2);
    }

    .btn-cta-gold:hover {
      background: #003366;
      transform: translateY(-2px);
      box-shadow: 0 12px 28px rgba(0, 33, 71, 0.3);
    }

    @keyframes zoomIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    @media print {
      body * { visibility: hidden; }
      .voucher-card, .voucher-card * { visibility: visible; }
      .voucher-card { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; border: none; }
      .no-print { display: none !important; }
    }
  </style>
@endpush

@section('content')

<div class="success-page">
  <div class="voucher-card">
    <div class="icon-circle">
      <i class="bi bi-check-lg"></i>
    </div>
    <h1>Reservasi Berhasil!</h1>
    <p class="subtitle">
      Terima kasih, <strong>{{ $booking->name ?? 'Tamu' }}</strong>. Reservasi Anda di Web Hotel telah terkonfirmasi secara resmi.
    </p>

    
    <div class="email-notice-box">
      <i class="bi bi-envelope-check-fill"></i>
      <div>
        <strong style="color:#14532d; display:block; margin-bottom:2px;">📧 e-Voucher Telah Dikirim ke Email Anda</strong>
        Salinan e-Voucher resmi dan panduan check-in telah dikirimkan ke <strong>{{ $booking->email ?? '' }}</strong>. Silakan periksa kotak masuk (inbox) atau folder spam Anda.
      </div>
    </div>

    @if($booking)
      <div class="voucher-details">
        <div style="text-align:center;margin-bottom:18px;padding-bottom:14px;border-bottom:2px solid #002147;">
          <span style="font-size:0.75rem;color:#64748b;letter-spacing:1.5px;text-transform:uppercase;font-weight:700;">Order Reference ID / Kode Voucher</span><br>
          <strong style="font-size:1.3rem;color:#002147;font-family:monospace;letter-spacing:1px;">{{ $booking->midtrans_order_id }}</strong>
        </div>
        <div class="voucher-row">
          <strong>Status Pembayaran:</strong>
          <span>
            @if($booking->payment_status === 'pay_at_hotel')
              <span class="status-badge status-hotel">🏨 Bayar di Hotel (Pay at Hotel)</span>
            @else
              <span class="status-badge status-paid">✓ Lunas (Paid)</span>
            @endif
          </span>
        </div>
        <div class="voucher-row"><strong>Nama Tamu:</strong> <span>{{ $booking->name }}</span></div>
        <div class="voucher-row">
          <strong>Tipe Kamar:</strong> 
          <span>
            @if($booking->room_details)
              @php $details = json_decode($booking->room_details, true); @endphp
              @if(is_array($details) && count($details) > 0)
                @foreach($details as $idx => $item)
                  {{ $item['name'] ?? 'Kamar' }} ({{ $item['qty'] ?? 1 }}x){{ $idx < count($details)-1 ? ', ' : '' }}
                @endforeach
              @else
                {{ $booking->room?->name }} ({{ $booking->room_count }} Kamar)
              @endif
            @else
              {{ $booking->room?->name }} ({{ $booking->room_count }} Kamar)
            @endif
          </span>
        </div>
        <div class="voucher-row"><strong>Jadwal Check-in:</strong> <span>{{ \Carbon\Carbon::parse($booking->checkin)->format('d M Y') }} (14:00 WIB)</span></div>
        <div class="voucher-row"><strong>Jadwal Check-out:</strong> <span>{{ \Carbon\Carbon::parse($booking->checkout)->format('d M Y') }} (12:00 WIB)</span></div>
        <div class="voucher-row"><strong>Tamu & Durasi:</strong> <span>{{ $booking->guests }} Tamu · {{ $booking->nights }} Malam</span></div>
        <div class="voucher-row" style="margin-top:16px;padding-top:16px;border-top:2px solid #e2e8f0;font-size:1.15rem;">
          <strong style="color:#002147;">Total Tarif Reservasi:</strong>
          <span style="color:#d4af37;font-family:'Playfair Display',serif;font-size:1.4rem;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>
      </div>
    @endif

    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;" class="no-print">
      <a href="{{ route('home') }}" class="btn-cta-gold"><i class="bi bi-house-door-fill"></i> Kembali ke Beranda</a>
      <a href="{{ route('booking.invoice', $booking->id) }}" target="_blank" class="btn-cta-gold" style="background:#f1f5f9;color:#002147;border:1.5px solid #cbd5e1;box-shadow:none;"><i class="bi bi-printer-fill"></i> Cetak / Simpan PDF</a>
    </div>
  </div>
</div>

@endsection

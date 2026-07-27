<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice Reservasi #{{ $booking->midtrans_order_id }}</title>
  <style>
    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 40px; color: #333; }
    .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 2px solid #C5A059; padding-bottom: 20px; }
    .header h1 { margin: 0; color: #0F172A; font-size: 28px; }
    .header .hotel-details { text-align: right; font-size: 14px; color: #666; }
    .booking-info { display: flex; justify-content: space-between; margin-bottom: 30px; }
    .booking-info div { width: 48%; }
    table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; margin-bottom: 20px; }
    table th, table td { padding: 12px; border-bottom: 1px solid #eee; }
    table th { background: #f8f8f8; color: #333; font-weight: bold; }
    .total-row td { font-weight: bold; font-size: 18px; border-top: 2px solid #333; }
    .footer { text-align: center; margin-top: 50px; font-size: 14px; color: #777; }
    
    @media print {
      body { padding: 0; -webkit-print-color-adjust: exact; }
      .invoice-box { box-shadow: none; border: none; padding: 0; }
      .no-print { display: none; }
    }
  </style>
</head>
<body onload="window.print()">
  <div class="no-print" style="text-align:center; margin-bottom: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; font-size:16px; background:#C5A059; color:white; border:none; border-radius:5px; cursor:pointer;">Cetak / Simpan PDF</button>
  </div>
  <div class="invoice-box">
    <div class="header">
      <div>
        <h1>WEB HOTEL</h1>
        <p style="margin: 5px 0 0; color:#888;">INVOICE & E-VOUCHER</p>
      </div>
      <div class="hotel-details">
        <strong>Web Hotel - Luxury App Project</strong><br>
        Jl. Sudirman No.123, Makassar<br>
        Tel: +62 811 0000 0000<br>
        Email: hello@webhotel.com
      </div>
    </div>
    
    <div class="booking-info">
      <div>
        <strong>Ditagihkan kepada:</strong><br>
        {{ $booking->name }}<br>
        {{ $booking->phone }}<br>
        {{ $booking->email }}
      </div>
      <div style="text-align: right;">
        <strong>Order ID:</strong> {{ $booking->midtrans_order_id }}<br>
        <strong>Tanggal Reservasi:</strong> {{ $booking->created_at->format('d M Y') }}<br>
        <strong>Status Pembayaran:</strong> <span style="color: {{ $booking->payment_status == 'paid' ? 'green' : 'red' }}; text-transform:uppercase;">{{ $booking->payment_status }}</span>
      </div>
    </div>
    
    <table>
      <thead>
        <tr>
          <th>Deskripsi Kamar</th>
          <th>Check-in</th>
          <th>Check-out</th>
          <th>Malam</th>
          <th style="text-align:right;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <strong>{{ $booking->room?->name ?? 'Tipe Kamar' }}</strong><br>
            <small>{{ $booking->room_count }} Kamar ({{ $booking->guests }} Tamu)</small>
          </td>
          <td>{{ \Carbon\Carbon::parse($booking->checkin)->format('d M Y') }}</td>
          <td>{{ \Carbon\Carbon::parse($booking->checkout)->format('d M Y') }}</td>
          <td>{{ $booking->nights }}</td>
          <td style="text-align:right;">Rp {{ number_format($booking->total_price + $booking->discount_amount, 0, ',', '.') }}</td>
        </tr>
        @if($booking->discount_amount > 0)
        <tr>
          <td colspan="4" style="text-align:right; color:#b91c1c;">Diskon Promo ({{ $booking->promo_code }})</td>
          <td style="text-align:right; color:#b91c1c;">- Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="total-row">
          <td colspan="4" style="text-align:right;">Total Keseluruhan</td>
          <td style="text-align:right;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>

    @if($booking->special_request)
    <div style="margin-top:20px; padding: 15px; background: #f9f9f9; border-left: 4px solid #C5A059;">
      <strong>Permintaan Khusus:</strong><br>
      {{ $booking->special_request }}
    </div>
    @endif

    <div class="footer">
      Ini adalah dokumen resmi reservasi. Harap tunjukkan dokumen ini saat check-in di resepsionis.<br>
      Terima kasih telah memilih <strong>Web Hotel</strong>!
    </div>
  </div>
</body>
</html>

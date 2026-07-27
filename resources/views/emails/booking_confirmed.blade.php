<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>e-Voucher Reservasi Web Hotel</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; color: #333333; margin: 0; padding: 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e1e8ed; }
        .header { background: #002147; padding: 30px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-family: 'Times New Roman', serif; letter-spacing: 1px; color: #d4af37; }
        .header p { margin: 5px 0 0; font-size: 13px; color: #cbd5e1; }
        .content { padding: 30px; }
        .content h2 { color: #002147; font-size: 20px; margin-top: 0; }
        .voucher-box { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #edf2f7; font-size: 14px; }
        .row:last-child { border-bottom: none; }
        .label { color: #64748b; font-weight: bold; }
        .value { color: #1e293b; font-weight: 600; text-align: right; }
        .total-row { font-size: 16px; color: #002147; font-weight: bold; margin-top: 10px; padding-top: 10px; border-top: 2px solid #cbd5e1; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-paid { background: #dcfce7; color: #15803d; }
        .badge-hotel { background: #fef9c3; color: #854d0e; }
        .footer { background: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Web Hotel</h1>
            <p>Jl. A. P. Pettarani No.03, Mannuruki, Kec. Tamalate, Kota Makassar, Sulawesi Selatan 90222</p>
        </div>
        <div class="content">
            <h2>Halo, {{ $booking->name }}!</h2>
            <p>Terima kasih telah memilih <strong>Web Hotel</strong>. Berikut adalah salinan resmi e-Voucher dan rincian reservasi kamar Anda. Harap tunjukkan email ini saat melakukan proses Check-in di resepsionis.</p>

            <div class="voucher-box">
                <div style="text-align:center;margin-bottom:15px;padding-bottom:10px;border-bottom:1px solid #e2e8f0;">
                    <span style="font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:1px;">Order Reference ID</span><br>
                    <strong style="font-size:18px;color:#002147;">{{ $booking->midtrans_order_id }}</strong>
                </div>
                <div class="row">
                    <span class="label">Status Pembayaran:</span>
                    <span class="value">
                        @if($booking->payment_status === 'pay_at_hotel')
                            <span class="badge badge-hotel">🏨 Bayar di Hotel</span>
                        @else
                            <span class="badge badge-paid">✓ Lunas (Paid)</span>
                        @endif
                    </span>
                </div>
                <div class="row">
                    <span class="label">Tipe Kamar:</span>
                    <span class="value">{{ $booking->room?->name }} ({{ $booking->room_count }} Kamar)</span>
                </div>
                <div class="row">
                    <span class="label">Jadwal Check-in:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($booking->checkin)->format('d M Y') }} (14:00 WIB)</span>
                </div>
                <div class="row">
                    <span class="label">Jadwal Check-out:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($booking->checkout)->format('d M Y') }} (12:00 WIB)</span>
                </div>
                <div class="row">
                    <span class="label">Jumlah Tamu & Durasi:</span>
                    <span class="value">{{ $booking->guests }} Tamu · {{ $booking->nights }} Malam</span>
                </div>
                @if($booking->notes)
                <div class="row">
                    <span class="label">Catatan Tambahan:</span>
                    <span class="value">{{ $booking->notes }}</span>
                </div>
                @endif
                <div class="row total-row">
                    <span>Total Tarif Reservasi:</span>
                    <span style="color:#d4af37;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <p style="font-size:13px;color:#475569;line-height:1.5;">
                <strong>Catatan Penting:</strong><br>
                1. Waktu Check-in standar dimulai pukul 14:00 WIB dan Check-out pukul 12:00 WIB.<br>
                2. Jika memilih opsi "Bayar di Hotel", pembayaran dapat diselesaikan menggunakan tunai, kartu debit/kredit, atau QRIS saat tiba di resepsionis.<br>
                3. Untuk pertanyaan atau perubahan jadwal, silakan hubungi tim reservasi kami di <strong>+62 811-4497-878</strong>.
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Web Hotel · All Rights Reserved.<br>Icon of the East — Grand & Luxury Experience.</p>
        </div>
    </div>
</body>
</html>

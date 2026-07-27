@extends('layouts.app')

@section('title', 'Web Hotel — Reservasi & Booking Resmi')
@section('description', 'Reservasi kamar mewah di Web Hotel. Proses cepat, aman, dan bergaransi harga terbaik.')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/booking.css') }}" />
@endpush

@section('content')

  
  <script>
    window.ROOMS = @json($roomsJs);
    @if($selectedRoom)
      window.SELECTED_ROOM_ID = "{{ $selectedRoom->id }}";
    @endif
  </script>

  
  <section class="hero image-hero">
    <div class="hero-bg" style="background-image:url('{{ asset('images/slider/slider3.jpg') }}')"></div>
    <div class="overlay"></div>
    <div class="content">
      <h1><i class="bi bi-stars"></i> Reservasi Web Hotel</h1>
      <p>Icon of the East — Kemewahan Berkelas · Pelayanan Eksklusif · Lokasi Strategis di Jantung Kota</p>
    </div>
  </section>

  
  <div class="booking-wrapper">
    <div class="form-card">

      
      <div class="form-left">
        <h2>Formulir Reservasi Resmi</h2>
        <p class="subtitle">Langkah mudah untuk mengamankan kamar mewah dan akomodasi berkelas Anda</p>

        
        <div class="steps">
          <div class="step active" data-step="1" onclick="goToStep(1)">1. Pilih Tanggal & Kamar</div>
          <div class="step" data-step="2" onclick="goToStep(2)">2. Data Tamu & Pembayaran</div>
        </div>

        
        <div class="step-panel" id="step-1">
          
          <div class="search-dates-grid">
            <div class="field" style="margin-bottom:0;">
              <label for="checkin">Tanggal Check-in</label>
              <input id="checkin" type="date" class="input" />
            </div>
            <div class="field" style="margin-bottom:0;">
              <label for="checkout">Tanggal Check-out</label>
              <input id="checkout" type="date" class="input" />
            </div>
            <div class="field" style="margin-bottom:0;">
              <label for="guests">Jumlah Tamu</label>
              <div class="qty-counter" style="width:100%; display:flex; justify-content:space-between; height: 49px; padding: 6px 12px; background:#FCFBF9; border:1.5px solid #D6D2C4; border-radius:14px;">
                <button type="button" onclick="changeGuestQty(-1)" style="border-radius:8px; background:#F8F6F0; width:34px; height:34px;"><i class="bi bi-dash"></i></button>
                <span id="guest-disp" style="font-weight:700; font-size:1rem; line-height:34px; color:var(--navy);">2 Tamu</span>
                <input type="hidden" id="guests" value="2">
                <button type="button" onclick="changeGuestQty(1)" style="border-radius:8px; background:#F8F6F0; width:34px; height:34px;"><i class="bi bi-plus"></i></button>
              </div>
            </div>
          </div>

          <div class="field" style="margin-top: 28px;">
            <label><i class="bi bi-grid-fill" style="color:var(--gold)"></i> Katalog Kamar Resmi Web Hotel</label>
            <p class="muted-small" style="margin-bottom: 14px; color:#57534E;">
              Silakan sesuaikan jumlah kamar dengan tombol <strong>+ / -</strong> di setiap kartu, lalu klik <strong>Pilih Kamar Ini</strong> untuk mengamankan harga terbaik.
            </p>
            
            <div class="rooms-container">
              <div class="rooms-grid" id="roomsGrid">
                @foreach($allRooms as $room)
                  <div class="room-card"
                       data-room="{{ $room->id }}"
                       data-price="{{ $room->price }}"
                       data-capacity="{{ $room->capacity }}"
                       onclick="selectRoomCard('{{ $room->id }}')">
                    <span class="badge-selected"><i class="bi bi-patch-check-fill"></i> KAMAR TERPILIH</span>
                    <img class="room-thumb"
                         src="{{ asset($room->image ?? 'images/slider/slider1.jpg') }}"
                         alt="{{ $room->name }}">
                    <div class="room-info">
                      <h4>{{ $room->name }}</h4>
                      <p class="price">Rp {{ number_format($room->price, 0, ',', '.') }} <span style="font-size:0.8rem;color:#78716C;font-weight:normal;">/ malam</span></p>
                      <div class="muted-small">
                        <span><i class="bi bi-person-fill" style="color:var(--gold)"></i> {{ $room->capacity }} Tamu</span>
                        <span><i class="bi bi-geo-alt-fill" style="color:var(--gold)"></i> {{ $room->floor_location ?: 'Lantai 3 - 8' }}</span>
                        <span><i class="bi bi-wifi" style="color:var(--gold)"></i> Free Wi-Fi</span>
                        <span><i class="bi bi-cup-hot-fill" style="color:var(--gold)"></i> Sarapan Included</span>
                      </div>
                      <div class="stock-info-box" id="stock-badge-{{ $room->id }}" style="margin-top:8px;font-size:0.85rem;font-weight:700;">
                        <span style="color:#64748b;"><i class="bi bi-clock-history"></i> Mengecek stok...</span>
                      </div>
                    </div>
                    <div class="room-action" onclick="event.stopPropagation();">
                      <div class="qty-counter" title="Atur Jumlah Kamar yang Dipesan">
                        <button type="button" class="btn-qty-minus" onclick="changeRoomQty('{{ $room->id }}', -1)"><i class="bi bi-dash"></i></button>
                        <span class="qty-num" id="qty-disp-{{ $room->id }}">1</span>
                        <button type="button" class="btn-qty-plus" onclick="changeRoomQty('{{ $room->id }}', 1)"><i class="bi bi-plus"></i></button>
                      </div>
                      <button type="button" class="btn-select-room" onclick="selectRoomCard('{{ $room->id }}')">
                        <i class="bi bi-check2-circle"></i> Pilih Kamar Ini
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          <div style="margin-top:32px; display:flex; gap:14px;">
            <button type="button" class="confirm-btn" id="to-step-2">
              <i class="bi bi-arrow-right-circle-fill"></i> Lanjutkan ke Data Tamu & Pembayaran
            </button>
          </div>
        </div>

        
        <div class="step-panel" id="step-2" style="display:none">
          <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
            @csrf
            <input type="hidden" name="room_id"     id="form_room_id">
            <input type="hidden" name="checkin"     id="form_checkin">
            <input type="hidden" name="checkout"    id="form_checkout">
            <input type="hidden" name="guests"      id="form_guests">
            <input type="hidden" name="nights"      id="form_nights">
            <input type="hidden" name="total_price" id="form_total">
            <input type="hidden" name="room_count"  id="form_qty">
            <input type="hidden" name="room_details" id="form_room_details">

            <div class="field">
              <label>Nama Lengkap (Sesuai KTP / Paspor)</label>
              <input id="full_name" name="name" class="input" required placeholder="Masukkan nama lengkap pemesan">
            </div>
            <div class="field">
              <label>Alamat Email Aktif</label>
              <input id="email" name="email" class="input" type="email" required placeholder="contoh@domain.com (untuk pengiriman otomatis e-Voucher)">
            </div>
            <div class="field">
              <label for="phone">Nomor HP</label>
              <input type="tel" id="phone" name="phone" class="input" placeholder="08xxxx" required>
            </div>

            <div class="field">
              <label for="special_request">Permintaan Khusus (Opsional)</label>
              <textarea id="special_request" name="special_request" class="input" rows="2" placeholder="Cth: Early check-in, Honeymoon setup, Non-smoking room"></textarea>
            </div>

            <div class="field" style="margin-bottom: 28px;">
              <label for="promo_code">Kode Promo / Voucher</label>
              <div style="display:flex; gap:10px;">
                <input type="text" id="promo_code" name="promo_code" class="input" placeholder="Masukkan kode promo" style="text-transform:uppercase;">
                <button type="button" id="btn-check-promo" class="confirm-btn" style="width:auto; padding: 0 20px; font-size:0.95rem;">Terapkan</button>
              </div>
              <div id="promo-message" style="margin-top:8px; font-size:0.85rem; font-weight:600;"></div>
            </div>

            
            <div class="payment-methods-box">
              <h4><i class="bi bi-shield-lock-fill" style="color:var(--gold)"></i> Pilihan Metode Pembayaran</h4>

              <label class="payment-option-card">
                <input type="radio" name="payment_method" value="midtrans" checked>
                <div class="payment-option-text">
                  <span class="opt-title">💳 Pembayaran Online Instan (Midtrans Gateway)</span>
                  <div class="opt-desc">Bayar aman dengan konfirmasi otomatis langsung terhubung ke sistem reservasi hotel.</div>
                  <div class="payment-badges">
                    <span class="badge-item">BCA VA</span>
                    <span class="badge-item">Mandiri VA</span>
                    <span class="badge-item">BNI VA</span>
                    <span class="badge-item">GoPay</span>
                    <span class="badge-item">OVO</span>
                    <span class="badge-item">DANA</span>
                    <span class="badge-item">QRIS</span>
                    <span class="badge-item">Kartu Kredit</span>
                  </div>
                </div>
              </label>

              <label class="payment-option-card">
                <input type="radio" name="payment_method" value="pay_at_hotel">
                <div class="payment-option-text">
                  <span class="opt-title">🏨 Bayar di Hotel (Pay at Hotel)</span>
                  <div class="opt-desc">Reservasi dijamin tanpa pembayaran awal. Pembayaran diselesaikan saat proses check-in di resepsionis.</div>
                </div>
              </label>
            </div>

            <div style="display:flex; gap:16px; margin-top:32px;">
              <button type="submit" class="confirm-btn">
                <i class="bi bi-check-circle-fill"></i> Konfirmasi & Bayar Sekarang
              </button>
              <button type="button" id="back-to-1" class="confirm-btn" style="flex:0 0 160px; background:#F8F6F0; color:#1C1917; border:1.5px solid #D6D2C4;">
                Kembali
              </button>
            </div>

            <div style="text-align:center; margin-top:22px; font-size:0.86rem; color:var(--text-muted);">
              ✨ Garansi Harga Terbaik · 🔒 Transaksi Terenkripsi 256-Bit SSL · 🛎️ Layanan Resepsionis 24 Jam
            </div>
          </form>
        </div>
      </div>

      
      <aside class="form-right">
        <div class="summary-card">
          <h3>Rincian Reservasi</h3>
          <div class="summary-row">
            <span>Check-in Date:</span>
            <span id="sum-checkin">—</span>
          </div>
          <div class="summary-row">
            <span>Check-out Date:</span>
            <span id="sum-checkout">—</span>
          </div>
          <div class="summary-row">
            <span>Jumlah Tamu:</span>
            <span id="sum-guests">—</span>
          </div>
          <div class="summary-row">
            <span>Tipe Kamar:</span>
            <span id="sum-room">—</span>
          </div>
          <div class="summary-row">
            <span>Tarif per Malam:</span>
            <span id="sum-price">—</span>
          </div>
          <div class="summary-row">
            <span>Durasi Menginap:</span>
            <span id="sum-nights">—</span>
          </div>
          <div class="summary-row">
            <span>Jumlah Kamar:</span>
            <span id="sum-qty">1 Kamar</span>
          </div>
          <div class="summary-row total">
            <span>Total Estimasi:</span>
            <span id="sum-total">—</span>
          </div>
          <p style="font-size:0.83rem; color:#D6D2C4; margin-top:18px; line-height:1.5; opacity:0.9;">
            * Harga estimasi resmi Web Hotel. e-Voucher reservasi resmi akan otomatis dikirimkan ke email Anda setelah konfirmasi.
          </p>
        </div>
      </aside>

    </div>
  </div>

@endsection

@push('scripts')
  <script src="{{ asset('js/booking.js') }}"></script>
@endpush

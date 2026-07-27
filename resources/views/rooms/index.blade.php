@extends('layouts.app')

@section('title', 'Web Hotel — Our Rooms')
@section('description', 'Explore our luxury rooms at Web Hotel. Book your stay today.')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/rooms.css') }}" />
@endpush

@section('content')

  <section class="rooms-section" id="rooms">
    <h2 class="section-title">Our Luxury Rooms</h2>

    @foreach($rooms as $room)
      <div class="room-card">
        
        <div class="room-left">
          <div class="slider" id="slider{{ $room->id }}">
            <div class="slides">
              @foreach($room->images as $img)
                <img src="{{ asset($img->file_path) }}" alt="{{ $room->name }}" />
              @endforeach
            </div>
            <button class="arrow prev" onclick="prevSlide('slider{{ $room->id }}')">&#10094;</button>
            <button class="arrow next" onclick="nextSlide('slider{{ $room->id }}')">&#10095;</button>
            <div class="dots" id="dots-slider{{ $room->id }}"></div>
          </div>
        </div>

        
        <div class="room-right">
          <h3 class="room-title">{{ $room->name }}</h3>
          <p class="room-desc">{{ $room->short_description }}</p>

          @if($room->facilitiesByCategory->has('utama'))
            <ul class="amenities utama">
              @foreach($room->facilitiesByCategory['utama']->take(4) as $f)
                <li><i class="bi bi-check2-square"></i> {{ $f->facility_name }}</li>
              @endforeach
            </ul>
          @endif

          @if($room->facilitiesByCategory->has('kamar'))
            <ul class="amenities">
              @foreach($room->facilitiesByCategory['kamar']->take(4) as $f)
                <li><i class="bi bi-check2-square"></i> {{ $f->facility_name }}</li>
              @endforeach
            </ul>
          @endif

          <div class="room-footer">
            <span class="room-price">
              Rp {{ number_format($room->price, 0, ',', '.') }} <small>/ malam</small>
            </span>
          </div>

          <div class="room-buttons">
            <a href="#" class="btn-detail" data-modal="modal{{ $room->id }}">View Detail</a>
            <a href="{{ route('booking', ['room_id' => $room->id]) }}" class="btn-book">Book Now</a>
          </div>
        </div>
      </div>

      
      <div class="modal-overlay" id="modal{{ $room->id }}">
        <div class="modal-content two-column">
          <span class="modal-close" data-close-modal="modal{{ $room->id }}">&times;</span>

          
          <div class="modal-left">
            <div class="room-title-modal">
              <h3>{{ $room->name }}</h3>
            </div>

            <div class="slider" id="modal-slider{{ $room->id }}">
              <div class="slides">
                @foreach($room->images as $img)
                  <img src="{{ asset($img->file_path) }}" alt="{{ $room->name }}" />
                @endforeach
              </div>
              <button class="arrow prev" onclick="prevSlide('modal-slider{{ $room->id }}')">&#10094;</button>
              <button class="arrow next" onclick="nextSlide('modal-slider{{ $room->id }}')">&#10095;</button>
            </div>

            <div class="slide-counter" id="counter-modal-slider{{ $room->id }}">
              1/{{ $room->images->count() }}
            </div>

            <div class="thumbnails" id="thumbnails-modal-slider{{ $room->id }}">
              @foreach($room->images as $i => $img)
                <img src="{{ asset($img->file_path) }}"
                     onclick="goToSlide('modal-slider{{ $room->id }}', {{ $i }})"
                     class="thumbnail {{ $i === 0 ? 'active' : '' }}" />
              @endforeach
            </div>
          </div>

          
          <div class="modal-right">
            <div class="modal-body">
              <h4>Info Kamar</h4>
              <div class="room-info-grid">
                <div class="info-item">
                  <i class="fas fa-user-friends"></i>
                  <span>{{ $room->capacity }} Tamu</span>
                </div>
              </div>

              @if($room->facilitiesByCategory->has('utama'))
                <h4>Fasilitas Utama</h4>
                <ul class="facility-list grid-list">
                  @foreach($room->facilitiesByCategory['utama'] as $f)
                    <li>{{ $f->facility_name }}</li>
                  @endforeach
                </ul>
              @endif

              @if($room->facilitiesByCategory->has('kamar'))
                <h4>Fasilitas Kamar</h4>
                <ul class="facility-list grid-list">
                  @foreach($room->facilitiesByCategory['kamar'] as $f)
                    <li>{{ $f->facility_name }}</li>
                  @endforeach
                </ul>
              @endif

              @if($room->facilitiesByCategory->has('bathroom'))
                <h4>Perlengkapan Kamar Mandi</h4>
                <ul class="facility-list grid-list">
                  @foreach($room->facilitiesByCategory['bathroom'] as $f)
                    <li>{{ $f->facility_name }}</li>
                  @endforeach
                </ul>
              @endif

              <h4>Deskripsi Kamar</h4>
              <p>{{ $room->full_description }}</p>
            </div>

            <div class="modal-footer">
              <span class="room-price">
                Rp {{ number_format($room->price, 0, ',', '.') }} <small>/ malam</small>
              </span>
              <a href="{{ route('booking', ['room_id' => $room->id]) }}" class="btn-book-modal">Book Now</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach

    
    <div class="image-popup" id="imagePopup">
      <div class="image-popup-content">
        <div class="image-wrapper">
          <img id="popupImg" src="" alt="Preview" />
          <span class="image-popup-close" id="popupClose">&times;</span>
        </div>
      </div>
    </div>
  </section>

@endsection

@push('scripts')
  <script src="{{ asset('js/rooms.js') }}"></script>
@endpush

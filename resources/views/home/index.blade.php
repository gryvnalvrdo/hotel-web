@extends('layouts.app')

@section('title', 'Web Hotel — Home')
@section('description', 'Welcome to Web Hotel. Experience luxury and comfort in every detail.')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/home.css') }}" />
@endpush

@section('content')

  
  <section class="hero" id="home">
    <div class="slides">
      @foreach($heroSlides as $i => $slide)
        <div class="slide {{ $i === 0 ? 'active' : '' }}">
          <img src="{{ asset($slide->image_path) }}" alt="Hero Slide {{ $i + 1 }}">
          <div class="overlay"></div>
          <div class="hero-text">
            @if($i === 0)
              <h1>Welcome to <span>Luxury</span></h1>
              <p>Experience elegance and comfort in every detail.</p>
              <a href="{{ route('booking') }}" class="btn-cta">Book Now</a>
            @elseif($i === 1)
              <h1>Exclusive <span>Rooms</span></h1>
              <p>Designed to give you a premium living experience.</p>
              <a href="{{ route('rooms') }}" class="btn-cta">Explore</a>
            @elseif($i === 2)
              <h1>Unforgettable <span>Stay</span></h1>
              <p>Where luxury meets comfort in the heart of the city.</p>
              <a href="#facilities" class="btn-cta">Get Started</a>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    
    <div class="dots">
      @foreach($heroSlides as $i => $slide)
        <span class="dot {{ $i === 0 ? 'active' : '' }}"></span>
      @endforeach
    </div>
  </section>

  
  <section id="rooms" class="section rooms">
    <div class="section-head">
      <h2 class="section-title">Our Favorite Room</h2>
      <p class="tagline">Experience luxury and comfort</p>
    </div>

    <div class="room-grid">
      @foreach($rooms as $room)
        <article class="room-card reveal" data-delay="0">
          <div class="room-media">
            <img src="{{ asset($room->thumbnail ?? '') }}"
                 alt="{{ $room->name }}"
                 class="image-popup-trigger">
            <a href="{{ route('booking', ['room_id' => $room->id]) }}" class="book-ribbon">
              <i class="bi bi-lightning-charge"></i>
              <span>BOOK<br>NOW</span>
            </a>
          </div>

          <div class="room-body">
            <div class="room-title">
              <h3>{{ $room->name }}</h3>
              <div class="room-tags">
                @foreach($room->mainFacilities as $f)
                  <span class="tag">
                    <i class="bi bi-check2-square"></i> {{ $f->facility_name }}
                  </span>
                @endforeach
              </div>
            </div>

            <ul class="amenities">
              @foreach($room->roomFacilities as $f)
                <li><i class="bi bi-check2-square"></i> {{ $f->facility_name }}</li>
              @endforeach
            </ul>
          </div>
        </article>
      @endforeach
    </div>
  </section>

  <div class="section-divider"></div>

  
  <div class="image-popup" id="imagePopup">
    <div class="image-popup-content">
      <div class="image-wrapper">
        <img id="popupImg" src="" alt="Preview">
        <span class="image-popup-close" id="popupClose">&times;</span>
      </div>
    </div>
  </div>

  
  <section id="facilities">
    <div class="section-head">
      <h2 class="section-title">Our Facilities</h2>
      <p class="tagline">Enjoy world-class services and amenities</p>
    </div>

    <div class="facilities-grid">
      @foreach($facilities as $facility)
        <div class="facility-card">
          <img src="{{ asset($facility->images->first()?->image_path ?? '') }}"
               alt="{{ $facility->title }}"
               class="modal-trigger"
               data-modal="facilityModal{{ $facility->id }}">

          <h3>{{ $facility->title }}</h3>
          <p>{{ $facility->description }}</p>

          <button class="see-more-btn" data-modal="facilityModal{{ $facility->id }}">
            See More
          </button>
        </div>
      @endforeach
    </div>
  </section>

  
  @foreach($facilities as $facility)
    <div id="facilityModal{{ $facility->id }}" class="modal-overlay">
      <div class="modal-content">
        <span class="modal-close">&times;</span>
        <div class="carousel-container">
          <div class="carousel-slide">
            @foreach($facility->images as $img)
              <img src="{{ asset($img->image_path) }}" alt="{{ $facility->title }}">
            @endforeach
          </div>
          <button class="prev arrow">&#10094;</button>
          <button class="next arrow">&#10095;</button>
        </div>
      </div>
    </div>
  @endforeach

  <div class="section-divider"></div>

  
  <section class="location">
    <div class="section-head">
      <h2 class="section-title">Our Location</h2>
      <p class="tagline">Find us at Web Hotel</p>
    </div>
    <div class="map-container">
      <iframe
        src="https://www.google.com/maps?q=Hotel+Hotel+Makassar&output=embed"
        width="100%"
        height="400"
        style="border:0;"
        allowfullscreen=""
        loading="lazy">
      </iframe>
    </div>
  </section>

@endsection

@push('scripts')
  <script src="{{ asset('js/home.js') }}"></script>
@endpush

@extends('layouts.app')

@section('title', 'Grand Lumina — Conference Rooms')
@section('description', 'Book our premium conference rooms at Grand Lumina Hotel.')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/conference.css') }}" />
@endpush

@section('content')

  <section class="conference-section" id="conference">
    <h2 class="section-title">Conference Rooms</h2>

    <div class="rooms-grid">
      @foreach($conferenceRooms as $index => $room)
        <div class="room-card">
          <h3>{{ $room->name }}</h3>

          {{-- Slider --}}
          <div class="room-slider" id="slider{{ $room->id }}">
            <button class="prev" onclick="prevSlide({{ $index }})">&#10094;</button>
            <div class="slides">
              @foreach($room->images as $img)
                <img src="{{ asset($img->image_path) }}"
                     alt="{{ $room->name }}"
                     onclick="openPopup(this)">
              @endforeach
            </div>
            <button class="next" onclick="nextSlide({{ $index }})">&#10095;</button>
            <div class="slider-dots" id="dots-slider{{ $room->id }}"></div>
          </div>

          {{-- Info --}}
          <div class="room-info">
            @if($room->description)
              <p class="room-desc">{{ $room->description }}</p>
            @endif
            <div class="info-grid">
              <div class="info-item">
                <i class="bi bi-arrows-fullscreen"></i>
                <span>{{ $room->width }}m × {{ $room->length }}m</span>
              </div>
              <div class="info-item">
                <i class="bi bi-people-fill"></i>
                <span>{{ $room->capacity }} orang</span>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- Image Popup --}}
  <div class="image-popup" id="imagePopup">
    <div class="image-popup-content">
      <div class="image-wrapper">
        <div class="popup-slides" id="popupSlides"></div>
        <span class="image-popup-close" id="popupClose">&times;</span>
        <span class="image-popup-prev" onclick="prevPopup()">&#10094;</span>
        <span class="image-popup-next" onclick="nextPopup()">&#10095;</span>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script src="{{ asset('js/conference.js') }}"></script>
@endpush

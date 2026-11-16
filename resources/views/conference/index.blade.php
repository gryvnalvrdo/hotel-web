@extends('layouts.app')

@section('title', 'Web Hotel | Conference Rooms')
@section('description', 'Book our premium conference rooms at Web Hotel.')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/conference.css') }}?v=20261116" />
@endpush

@section('content')

  <section class="conference-section" id="conference">
    <h2 class="section-title">Conference Rooms</h2>

    <div class="rooms-grid">
      @foreach($conferenceRooms as $index => $room)
        <div class="room-card">
          <h3>{{ $room->name }}</h3>

          
          <div class="room-slider" id="slider{{ $room->id }}">
            <button type="button" class="prev" aria-label="Previous slide">&#10094;</button>
            <div class="slides">
              @foreach($room->images as $img)
                @php
                  $imgSrc = \Illuminate\Support\Str::startsWith($img->image_path, ['http://', 'https://'])
                      ? $img->image_path
                      : asset($img->image_path);
                @endphp
                <img src="{{ $imgSrc }}"
                     alt="{{ $room->name }}"
                     loading="lazy"
                     onerror="this.onerror=null;this.src='{{ asset('images/conferences/sandeq1.jpg') }}';">
              @endforeach
            </div>
            <button type="button" class="next" aria-label="Next slide">&#10095;</button>
            <div class="slider-dots" id="dots-slider{{ $room->id }}"></div>
          </div>

          
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

  
  <div class="image-popup" id="imagePopup">
    <div class="image-popup-content">
      <div class="image-wrapper">
        <div class="popup-slides" id="popupSlides"></div>
        <span class="image-popup-close" id="popupClose">&times;</span>
        <span class="image-popup-prev">&#10094;</span>
        <span class="image-popup-next">&#10095;</span>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script src="{{ asset('js/conference.js') }}"></script>
@endpush

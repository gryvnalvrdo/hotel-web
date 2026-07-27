<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Grand Lumina')</title>
  <meta name="description" content="@yield('description', 'Grand Lumina — Experience luxury and comfort in every detail.')">

  {{-- Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  {{-- Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  {{-- Global CSS --}}
  <link rel="stylesheet" href="{{ asset('css/global.css') }}" />

  {{-- Page-specific CSS --}}
  @stack('styles')

  {{-- CSRF Token --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  {{-- ========== LOADING SCREEN ========== --}}
  <div id="loading-screen">
    <div class="loading-logo">
      <h1>Grand Lumina</h1>
      <p>Makassar · Sulawesi Selatan</p>
    </div>
    <div class="loading-divider"></div>
    <div class="loading-bar-wrap">
      <div class="loading-bar"></div>
    </div>
  </div>

  {{-- ========== NAVBAR ========== --}}
  <header class="navbar-modern" id="navbar">
    <div class="logo">Hotel <span>Lumina</span></div>
    <nav>
      <ul class="nav-links">
        <div class="drawer-header">
          <span class="drawer-title">
            <span class="hotel">Hotel</span> <span class="Lumina">Lumina</span>
          </span>
        </div>
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('rooms') }}" class="{{ request()->routeIs('rooms') ? 'active' : '' }}">Rooms</a></li>
        <li><a href="{{ route('home') }}#facilities">Facilities</a></li>
        <li><a href="{{ route('conference') }}" class="{{ request()->routeIs('conference') ? 'active' : '' }}">Conference</a></li>
        <li><a href="#footer">About</a></li>
        <li><a href="{{ route('booking') }}" class="{{ request()->routeIs('booking*') ? 'active' : '' }}">Book Now</a></li>
      </ul>
    </nav>
    <div class="menu-toggle"><i class="bi bi-list"></i></div>
  </header>

  <div class="drawer-overlay"></div>

  {{-- ========== PAGE CONTENT ========== --}}
  @yield('content')

  {{-- ========== FOOTER ========== --}}
  <footer class="footer-modern" id="footer">
    <div class="footer-main">
      {{-- Branding --}}
      <div class="footer-brand">
        <h2>{{ $branding->hotel_name ?? 'Grand Lumina' }}</h2>
        <p>{!! nl2br(e($branding->tagline ?? '')) !!}</p>

        {{-- Social Media --}}
        <div class="footer-social">
          @foreach($socials as $social)
            <a href="{{ $social->url }}" target="_blank">
              <i class="{{ $social->icon_class }}"></i>
            </a>
          @endforeach
        </div>

        {{-- Partners --}}
        <div class="footer-partners">
          <h4>Our Partners</h4>
          <div class="partner-logos">
            @foreach($partners as $partner)
              <a href="{{ $partner->url }}" target="_blank">
                <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}">
              </a>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Contact --}}
      <div class="footer-contact">
        <h3>Contact Us</h3>
        <ul>
          @foreach($contacts as $contact)
            <li>
              @if($contact->icon_class)
                <i class="{{ $contact->icon_class }}"></i>
              @endif
              {{ $contact->value }}
            </li>
          @endforeach
        </ul>
      </div>
    </div>

    {{-- Bottom --}}
    <div class="footer-bottom">
      <p>{{ $footerBottom->text ?? '© ' . date('Y') . ' Grand Lumina. All rights reserved.' }}</p>
    </div>
  </footer>

  {{-- Floating Buttons --}}
  <button id="backToTop" class="back-to-top">
    <i class="bi bi-arrow-up"></i>
  </button>

  <a href="https://wa.me/628114497878" class="whatsapp-float" target="_blank">
    <i class="bi bi-whatsapp"></i>
    <span>Contact Us</span>
  </a>

  {{-- Scripts --}}
  <script src="{{ asset('js/header_footer.js') }}"></script>
  @stack('scripts')

  {{-- Loading Screen Dismiss (Hanya muncul sekali per sesi agar tidak mengganggu saat ganti halaman) --}}
  <script>
    (function () {
      var ls = document.getElementById('loading-screen');
      if (!ls) return;

      if (sessionStorage.getItem('hotel_Lumina_loaded') === 'true') {
        ls.style.display = 'none';
        return;
      }

      var hide = function () {
        setTimeout(function () {
          ls.classList.add('hidden');
          sessionStorage.setItem('hotel_Lumina_loaded', 'true');
        }, 300);
      };
      if (document.readyState === 'complete') {
        setTimeout(hide, 800);
      } else {
        window.addEventListener('load', function () { setTimeout(hide, 800); });
      }
    })();
  </script>

</body>
</html>

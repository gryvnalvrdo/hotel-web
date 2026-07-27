<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Web Hotel')</title>
  <meta name="description" content="@yield('description', 'Web Hotel — Experience luxury and comfort in every detail.')">

  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  
  <link rel="stylesheet" href="{{ asset('css/global.css') }}" />

  
  @stack('styles')

  
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  
  <div id="loading-screen">
    <div class="loading-logo">
      <h1>Web Hotel</h1>
      <p>Makassar · Sulawesi Selatan</p>
    </div>
    <div class="loading-divider"></div>
    <div class="loading-bar-wrap">
      <div class="loading-bar"></div>
    </div>
  </div>

  
  <header class="navbar-modern" id="navbar">
    <div class="logo">Web <span>Hotel</span></div>
    <nav>
      <ul class="nav-links">
        <div class="drawer-header">
          <span class="drawer-title">
            <span class="hotel" style="color:var(--gold);">Web</span> <span class="Hotel" style="color:white;">Hotel</span>
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

  
  @yield('content')

  
  <footer class="footer-modern" id="footer">
    <div class="footer-main">
      
      <div class="footer-brand">
        <h2>Web Hotel</h2>
        <p>Luxury & Comfort Project<br>Sistem Reservasi & Manajemen Berbasis Laravel</p>

        <div class="footer-social">
            <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
        </div>
        
        <div class="footer-partners" style="margin-top:20px;">
          <h4 style="font-size:0.95rem; margin-bottom:12px; color:var(--gold);">Our Partners</h4>
          <div class="partner-logos" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
              <a href="https://agoda.com" target="_blank" style="color:var(--text); font-weight:600; text-decoration:none; background:#F8F6F0; padding:6px 12px; border-radius:8px; font-size:0.85rem;">Agoda</a>
              <a href="https://traveloka.com" target="_blank" style="color:var(--text); font-weight:600; text-decoration:none; background:#F8F6F0; padding:6px 12px; border-radius:8px; font-size:0.85rem;">Traveloka</a>
              <a href="https://booking.com" target="_blank" style="color:var(--text); font-weight:600; text-decoration:none; background:#F8F6F0; padding:6px 12px; border-radius:8px; font-size:0.85rem;">Booking.com</a>
          </div>
        </div>
      </div>
      
      <div class="footer-contact">
        <h3>Contact Us</h3>
        <ul>
            <li><i class="bi bi-geo-alt-fill"></i> Jl. Fiktif Utama No. 123, Jakarta Selatan, 12345 (Fictitious Location)</li>
            <li><i class="bi bi-telephone-fill"></i> +62 811 0000 0000</li>
            <li><i class="bi bi-envelope-fill"></i> hello@webhotel.local</li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© {{ date('Y') }} Web Hotel Project. All rights reserved. (Not a real hotel)</p>
    </div>
  </footer>

  
  <button id="backToTop" class="back-to-top">
    <i class="bi bi-arrow-up"></i>
  </button>

  <a href="https://wa.me/628114497878" class="whatsapp-float" target="_blank">
    <i class="bi bi-whatsapp"></i>
    <span>Contact Us</span>
  </a>

  
  <script src="{{ asset('js/header_footer.js') }}"></script>
  @stack('scripts')

  
  <script>
    (function () {
      var ls = document.getElementById('loading-screen');
      if (!ls) return;

      if (sessionStorage.getItem('hotel_Hotel_loaded') === 'true') {
        ls.style.display = 'none';
        return;
      }

      var hide = function () {
        setTimeout(function () {
          ls.classList.add('hidden');
          sessionStorage.setItem('hotel_Hotel_loaded', 'true');
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

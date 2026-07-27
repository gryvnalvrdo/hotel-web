// ===============================
// NAVBAR TOGGLE (Mobile Drawer)
// ===============================
(function () {
  const header = document.querySelector('.navbar-modern');
  const menuToggle = document.querySelector('.menu-toggle');
  const navLinks = document.querySelector('.nav-links');

  if (!header || !menuToggle || !navLinks) return;

  // Buat overlay sekali saja
  let overlay = document.querySelector('.drawer-overlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'drawer-overlay';
    document.body.appendChild(overlay);
  }

  const icon = menuToggle.querySelector('i');

  function openMenu() {
    navLinks.classList.add('show');
    overlay.style.display = 'block';
    document.body.classList.add('menu-open');
    menuToggle.setAttribute('aria-expanded', 'true');
    if (icon && icon.classList.contains('bi-list')) {
      icon.classList.replace('bi-list', 'bi-x');
    }
  }

  function closeMenu() {
    navLinks.classList.remove('show');
    overlay.style.display = 'none';
    document.body.classList.remove('menu-open');
    menuToggle.setAttribute('aria-expanded', 'false');
    if (icon && icon.classList.contains('bi-x')) {
      icon.classList.replace('bi-x', 'bi-list');
    }
  }

  function toggleMenu() {
    navLinks.classList.contains('show') ? closeMenu() : openMenu();
  }

  // Events
  menuToggle.addEventListener('click', toggleMenu);
  overlay.addEventListener('click', closeMenu);
  navLinks.addEventListener('click', e => {
    if (e.target.closest('a')) closeMenu();
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeMenu();
  });
  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) closeMenu();
  });
})();

// === Back to Top Button ===
(function () {
  const backToTop = document.getElementById('backToTop');
  if (!backToTop) return;

  // Tampil saat scroll > 200px
  window.addEventListener('scroll', () => {
    if (window.scrollY > 200) {
      backToTop.classList.add('show');
    } else {
      backToTop.classList.remove('show');
    }
  });

  // Smooth scroll ke atas
  backToTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
})();
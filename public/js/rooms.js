const sliders = {};

function initSlider(sliderId) {
  const slider = document.getElementById(sliderId);
  if (!slider) return;
  if (sliders[sliderId]) return;

  const slides = slider.querySelector('.slides');
  const images = slides ? slides.querySelectorAll('img') : [];
  const dotsContainer = document.getElementById('dots-' + sliderId);

  sliders[sliderId] = { index: 0, slides, images, dotsContainer };

  if (dotsContainer) {
    dotsContainer.innerHTML = '';
    images.forEach((_, i) => {
      const dot = document.createElement('span');
      dot.addEventListener('click', () => goToSlide(sliderId, i));
      dotsContainer.appendChild(dot);
    });
  }
  if (!slider.closest('.modal-overlay')) {
    images.forEach(img => {
      img.style.cursor = 'pointer';
      img.addEventListener('click', () => {
        const modalId = sliderId.replace('slider', 'modal');
        openModal(modalId);
      });
    });
  }
  if (slider.closest('.modal-overlay')) {
    images.forEach(img => {
      img.style.cursor = 'zoom-in';
      img.addEventListener('click', () => openImagePopup(img.src));
    });
  }

  enableSwipe(sliderId);
  updateSlider(sliderId);
}

function updateSlider(sliderId) {
  const s = sliders[sliderId];
  if (!s || !s.slides) return;
  s.slides.style.transform = `translateX(-${s.index * 100}%)`;

  if (s.dotsContainer) {
    s.dotsContainer.querySelectorAll('span').forEach((dot, i) => {
      dot.classList.toggle('active', i === s.index);
    });
  }

  const thumbsContainer = document.getElementById(`thumbnails-${sliderId}`);
  if (thumbsContainer) {
    thumbsContainer.querySelectorAll('img').forEach((t, i) =>
      t.classList.toggle('active', i === s.index)
    );
  }

  const counter = document.getElementById('counter-' + sliderId);
  if (counter) counter.textContent = `${s.index + 1}/${s.images.length}`;
}

function nextSlide(sliderId) {
  const s = sliders[sliderId];
  if (!s) return;
  s.index = (s.index + 1) % s.images.length;
  updateSlider(sliderId);
}

function prevSlide(sliderId) {
  const s = sliders[sliderId];
  if (!s) return;
  s.index = (s.index - 1 + s.images.length) % s.images.length;
  updateSlider(sliderId);
}

function goToSlide(sliderId, i) {
  const s = sliders[sliderId];
  if (!s) return;
  s.index = i;
  updateSlider(sliderId);
}
function enableSwipe(sliderId) {
  const slider = document.getElementById(sliderId);
  if (!slider) return;
  let startX = 0;
  slider.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
  slider.addEventListener('touchend', e => {
    const diff = startX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) diff > 0 ? nextSlide(sliderId) : prevSlide(sliderId);
  }, { passive: true });
}
function openModal(id) {
  const modal = document.getElementById(id);
  if (!modal) { console.warn('Modal not found:', id); return; }
  modal.classList.add('active');
  document.body.classList.add('modal-open');
  document.body.style.overflow = 'hidden';
  const modalSlider = modal.querySelector('.slider');
  if (modalSlider) initSlider(modalSlider.id);
}

function closeModal(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.classList.remove('active');
  document.body.classList.remove('modal-open');
  document.body.style.overflow = '';
}

function closeAllModals() {
  document.querySelectorAll('.modal-overlay.active').forEach(m => {
    m.classList.remove('active');
  });
  document.body.classList.remove('modal-open');
  document.body.style.overflow = '';
}
function openImagePopup(src) {
  const popup = document.getElementById('imagePopup');
  const img   = document.getElementById('popupImg');
  if (!popup || !img) return;
  img.src = src;
  popup.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeImagePopup() {
  const popup = document.getElementById('imagePopup');
  if (!popup) return;
  popup.classList.remove('active');
  document.body.style.overflow = '';
}
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.slider').forEach(slider => initSlider(slider.id));
  document.addEventListener('click', e => {
    if (e.target.classList.contains('modal-overlay')) {
      closeAllModals();
    }
    const popup = document.getElementById('imagePopup');
    if (popup && e.target === popup) {
      closeImagePopup();
    }
  });
  document.addEventListener('click', e => {
    const btn = e.target.closest('[data-modal]');
    if (btn) {
      e.preventDefault();
      openModal(btn.getAttribute('data-modal'));
    }
    const closeBtn = e.target.closest('[data-close-modal]');
    if (closeBtn) {
      e.preventDefault();
      closeModal(closeBtn.getAttribute('data-close-modal'));
    }
  });
  const popupClose = document.getElementById('popupClose');
  if (popupClose) popupClose.addEventListener('click', closeImagePopup);
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      closeAllModals();
      closeImagePopup();
    }
  });
});

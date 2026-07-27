// ===============================
// Reveal Animation
// ===============================
(function () {
  const reveals = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const delay = entry.target.getAttribute('data-delay') || 0;
        setTimeout(() => entry.target.classList.add('show'), delay);
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.18 });

  reveals.forEach(el => io.observe(el));
})();


// ===============================
// Slider (Homepage Banner)
// ===============================
(function () {
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  if (!slides.length) return;

  let currentIndex = 0;

  function showSlide(index) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    slides[index].classList.add('active');
    dots[index].classList.add('active');
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
  }

  function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(currentIndex);
  }

  // autoplay
  setInterval(nextSlide, 4000);

  // dots control
  dots.forEach((dot, idx) => {
    dot.addEventListener('click', () => {
      currentIndex = idx;
      showSlide(currentIndex);
    });
  });

  // init
  showSlide(currentIndex);

  // optional: expose for manual control
  window.nextSlide = nextSlide;
  window.prevSlide = prevSlide;
})();


// ===============================
// Modal + Carousel (Final Clean)
// ===============================
(function () {
  // buka modal
  document.querySelectorAll(".see-more-btn, .modal-trigger").forEach(trigger => {
    trigger.addEventListener("click", function () {
      const modalId = this.getAttribute("data-modal");
      const modal = document.getElementById(modalId);

      if (modal) {
        modal.classList.add("active");
        document.body.style.overflow = "hidden"; // lock scroll

        // reset carousel ke awal
        const slide = modal.querySelector(".carousel-slide");
        const images = slide ? slide.querySelectorAll("img") : [];
        if (slide && images.length) {
          slide.style.transform = "translateX(0%)";
          slide.dataset.index = "0";
        }
      }
    });
  });

  // fungsi tutup modal
  function closeModal(modal) {
    modal.classList.remove("active");
    document.body.style.overflow = "auto"; // unlock scroll
  }

  // event close modal
  document.querySelectorAll(".modal-overlay").forEach(modal => {
    const closeBtn = modal.querySelector(".modal-close");

    if (closeBtn) {
      closeBtn.addEventListener("click", e => {
        e.stopPropagation();
        closeModal(modal);
      });
    }

    modal.addEventListener("click", e => {
      if (e.target === modal) closeModal(modal);
    });
  });

  // carousel dalam modal
  document.querySelectorAll(".modal-overlay").forEach(modal => {
    const slide = modal.querySelector(".carousel-slide");
    const images = slide ? slide.querySelectorAll("img") : [];
    const prevBtn = modal.querySelector(".prev");
    const nextBtn = modal.querySelector(".next");

    if (!slide || !images.length) return;

    function updateSlide(index) {
      slide.style.transform = `translateX(${-index * 100}%)`;
      slide.dataset.index = index;
    }

    if (images.length > 1) {
      prevBtn.style.display = "block";
      nextBtn.style.display = "block";

      prevBtn.addEventListener("click", e => {
        e.stopPropagation();
        let index = parseInt(slide.dataset.index || "0", 10);
        index = (index - 1 + images.length) % images.length;
        updateSlide(index);
      });

      nextBtn.addEventListener("click", e => {
        e.stopPropagation();
        let index = parseInt(slide.dataset.index || "0", 10);
        index = (index + 1) % images.length;
        updateSlide(index);
      });
    } else {
      prevBtn.style.display = "none";
      nextBtn.style.display = "none";
    }
  });
})();


// ===============================
// Image Popup (Single Image Preview)
// ===============================
(function () {
  const popup = document.getElementById("imagePopup");
  const popupImg = document.getElementById("popupImg");
  const popupClose = document.getElementById("popupClose");

  if (!popup || !popupImg) return;

  // buka popup ketika gambar ditekan
  document.querySelectorAll(".image-popup-trigger").forEach(img => {
    img.addEventListener("click", () => {
      popupImg.src = img.src;
      popup.classList.add("active"); // sesuai CSS
      document.body.style.overflow = "hidden";
    });
  });

  // tombol close
  if (popupClose) {
    popupClose.addEventListener("click", () => {
      popup.classList.remove("active");
      document.body.style.overflow = "auto";
    });
  }

  // klik di luar gambar
  popup.addEventListener("click", e => {
    if (e.target === popup) {
      popup.classList.remove("active");
      document.body.style.overflow = "auto";
    }
  });
})();
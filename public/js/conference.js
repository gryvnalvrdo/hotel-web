// =============================
// SLIDER
// =============================
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".room-slider").forEach((slider) => {
    const slides = slider.querySelector(".slides");
    const slideItems = slides.querySelectorAll(":scope > *");
    const prevBtn = slider.querySelector(".prev");
    const nextBtn = slider.querySelector(".next");
    const dotsContainer = slider.querySelector(".slider-dots");

    let index = 0;
    let interval = null;
    const autoSpeed = 2000; // kecepatan auto-slide saat hover
    const isMobile = window.matchMedia("(max-width: 820px)").matches;

    // Generate dots sesuai jumlah slide
    slideItems.forEach((_, i) => {
      const dot = document.createElement("span");
      dot.classList.add("dot");
      if (i === 0) dot.classList.add("active");

      dot.addEventListener("click", () => {
        index = i;
        updateSlide();
      });

      dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer.querySelectorAll(".dot");

    function updateSlide() {
      slides.style.transform = `translateX(-${index * 100}%)`;
      dots.forEach((dot, i) => {
        dot.classList.toggle("active", i === index);
      });
    }

    function showNext() {
      index = (index + 1) % slideItems.length;
      updateSlide();
    }

    function showPrev() {
      index = (index - 1 + slideItems.length) % slideItems.length;
      updateSlide();
    }

    // Event tombol
    nextBtn.addEventListener("click", showNext);
    prevBtn.addEventListener("click", showPrev);

    // Auto-slide hanya saat card di-hover (desktop saja)
    function startAutoSlide() {
      if (!interval) interval = setInterval(showNext, autoSpeed);
    }

    function stopAutoSlide() {
      clearInterval(interval);
      interval = null;
    }

    if (!isMobile) {
      const card = slider.closest(".room-card");
      card.addEventListener("mouseenter", startAutoSlide);
      card.addEventListener("mouseleave", stopAutoSlide);
    }

    // Popup event (ambil semua <img>)
    const imgs = slides.querySelectorAll("img");
    imgs.forEach((img, i) => {
      img.addEventListener("click", () => openPopup(imgs, i));
    });

    stopAutoSlide(); // pastikan awalnya mati
    updateSlide();
  });
});


// =============================
// POPUP SLIDER
// =============================
let popupIndex = 0;
let popupImages = [];

function openPopup(images, startIndex) {
  popupImages = Array.from(images);
  popupIndex = startIndex;

  const popup = document.getElementById("imagePopup");
  const slidesContainer = document.getElementById("popupSlides");

  slidesContainer.innerHTML = "";

  popupImages.forEach((img) => {
    const clone = document.createElement("img");
    clone.src = img.src;
    slidesContainer.appendChild(clone);
  });

  requestAnimationFrame(() => updatePopupSlide());

  popup.classList.remove("fade-out");
  popup.classList.add("active", "fade-in");
}

function closePopup() {
  const popup = document.getElementById("imagePopup");
  popup.classList.remove("fade-in");
  popup.classList.add("fade-out");
  setTimeout(() => popup.classList.remove("active", "fade-out"), 300);
}

function updatePopupSlide() {
  const slidesContainer = document.getElementById("popupSlides");
  slidesContainer.style.transform = `translateX(-${popupIndex * 100}%)`;
}

function nextPopup() {
  popupIndex = (popupIndex + 1) % popupImages.length;
  updatePopupSlide();
}

function prevPopup() {
  popupIndex = (popupIndex - 1 + popupImages.length) % popupImages.length;
  updatePopupSlide();
}

// =============================
// EVENT GLOBAL POPUP
// =============================
document.addEventListener("DOMContentLoaded", () => {
  const popup = document.getElementById("imagePopup");
  const closeBtn = document.getElementById("popupClose");

  closeBtn.addEventListener("click", closePopup);

  popup.addEventListener("click", (e) => {
    if (e.target === popup) closePopup();
  });

  document.addEventListener("keydown", (e) => {
    if (!popup.classList.contains("active")) return;
    if (e.key === "Escape") closePopup();
    if (e.key === "ArrowRight") nextPopup();
    if (e.key === "ArrowLeft") prevPopup();
  });
});
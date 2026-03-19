import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const initReviewsSwiper = (scope = document) => {
  const swiperElements = scope.querySelectorAll(
    '.reviews-swiper:not(.swiper-initialized)'
  );

  if (!swiperElements.length) return;

  swiperElements.forEach((swiperEl) => {
    const nextEl = swiperEl.querySelector('.__next');
    const prevEl = swiperEl.querySelector('.__prev');
    const paginationEl = swiperEl.querySelector('.swiper-pagination');

    new Swiper(swiperEl, {
      modules: [Navigation, Pagination],

      slidesPerView: 1.2,
      spaceBetween: 24,
      loop: true,

      pagination: {
        el: paginationEl,
        clickable: true,
      },

      navigation: {
        nextEl,
        prevEl,
      },

      breakpoints: {
        768: {
          slidesPerView: 2.5,
          spaceBetween: 24,
        },
        1024: {
          slidesPerView: 3.8,
          spaceBetween: 24,
        },
      },
    });
  });
};

// ✅ Jeśli ten plik jest ładowany lazy z app.js po DOMContentLoaded,
// to możemy zainicjalizować od razu.
initReviewsSwiper();

// ✅ Wsparcie dla podglądu / renderowania bloku w edytorze ACF
if (window.acf) {
  window.acf.addAction('render_block', (el) => {
    // el bywa jQuery-like; bezpiecznie bierzemy pierwszy element DOM
    const node = el?.[0] ?? el;
    if (node) initReviewsSwiper(node);
  });
}

export default initReviewsSwiper;


document.addEventListener('DOMContentLoaded', function () {
  // 1. Logika pokazywania przycisku "Zobacz całość"
  const reviewCards = document.querySelectorAll('.b-reviews .swiper-slide');

  reviewCards.forEach(card => {
    const reviewText = card.querySelector('.__txt');
    const moreButton = card.querySelector('.btn-more');

    if (reviewText && moreButton) {
      // Sprawdzamy, czy tekst faktycznie się nie mieści (jest ucięty)
      if (reviewText.scrollHeight > reviewText.clientHeight) {
        moreButton.classList.remove('hidden');
      }
    }
  });

  // 2. Logika obsługi popupu
  const popup = document.getElementById('review-popup');
  const popupContent = document.getElementById('review-popup-content');
  const closeButton = document.getElementById('review-popup-close');
  const moreButtons = document.querySelectorAll('.btn-more');

  moreButtons.forEach(button => {
    button.addEventListener('click', () => {
      // Znajdź tekst opinii powiązany z klikniętym przyciskiem
      const reviewText = button.previousElementSibling;
      if (reviewText) {
        popupContent.innerHTML = reviewText.innerHTML;
        popup.style.display = 'flex'; // Pokaż popup
      }
    });
  });

  // Funkcja zamykania popupu
  const closePopup = () => {
    popup.style.display = 'none';
  };

  // Zamykanie po kliknięciu przycisku 'x'
  if (closeButton) {
    closeButton.addEventListener('click', closePopup);
  }

  // Zamykanie po kliknięciu w tło
  if (popup) {
    popup.addEventListener('click', (e) => {
      if (e.target === popup) {
        closePopup();
      }
    });
  }

  // Zamykanie po naciśnięciu klawisza Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && popup.style.display === 'flex') {
      closePopup();
    }
  });
});
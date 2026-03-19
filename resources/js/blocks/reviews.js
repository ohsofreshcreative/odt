import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

/**
 * Zarządza logiką przycisku "Zobacz całość" i popupem dla opinii.
 * @param {HTMLElement} swiperInstanceEl - Główny element Swipera.
 */
const handleReviewPopupLogic = (swiperInstanceEl) => {
  const reviewSlides = swiperInstanceEl.querySelectorAll('.swiper-slide');
  const popup = document.getElementById('review-popup');
  const popupContent = document.getElementById('review-popup-content');
  const closeButton = document.getElementById('review-popup-close');

  // Jeśli nie ma popupu na stronie, nie kontynuuj
  if (!popup || !popupContent || !closeButton) {
    return;
  }

  // 1. Logika pokazywania przycisku "Zobacz całość"
  reviewSlides.forEach(slide => {
    const reviewText = slide.querySelector('.__txt');
    const moreButton = slide.querySelector('.btn-more');

    if (reviewText && moreButton) {
      // Sprawdzamy, czy tekst faktycznie się nie mieści (jest ucięty)
      const isTextOverflowing = reviewText.scrollHeight > reviewText.clientHeight;
      
      if (isTextOverflowing) {
        moreButton.classList.remove('hidden');
        
        // Upewniamy się, że nie dodajemy wielokrotnie tego samego listenera
        if (!moreButton.dataset.listenerAttached) {
          moreButton.addEventListener('click', () => {
            popupContent.innerHTML = reviewText.innerHTML;
            popup.style.display = 'flex';
          });
          moreButton.dataset.listenerAttached = 'true';
        }
      } else {
        moreButton.classList.add('hidden');
      }
    }
  });

  // 2. Logika zamykania popupu (definiowana tylko raz)
  if (!popup.dataset.closeListenerAttached) {
    const closePopup = () => {
      popup.style.display = 'none';
    };

    closeButton.addEventListener('click', closePopup);
    popup.addEventListener('click', (e) => {
      if (e.target === popup) closePopup();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && popup.style.display === 'flex') closePopup();
    });
    popup.dataset.closeListenerAttached = 'true';
  }
};

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
      // Uruchom naszą logikę po inicjalizacji i każdej zmianie slajdu
      on: {
        init: (swiper) => {
          handleReviewPopupLogic(swiper.el);
        },
        slideChange: (swiper) => {
          handleReviewPopupLogic(swiper.el);
        },
        resize: (swiper) => {
          handleReviewPopupLogic(swiper.el);
        }
      },
    });
  });
};

// Inicjalizujemy na starcie
document.addEventListener('DOMContentLoaded', () => {
    initReviewsSwiper();
});


// Wsparcie dla podglądu / renderowania bloku w edytorze ACF
if (window.acf) {
  window.acf.addAction('render_block', (el) => {
    const node = el?.[0] ?? el;
    if (node) initReviewsSwiper(node);
  });
}

export default initReviewsSwiper;
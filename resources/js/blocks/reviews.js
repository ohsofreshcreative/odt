import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

/**
 * Zarządza logiką przycisku "Zobacz całość" dla opinii.
 * @param {HTMLElement} swiperInstanceEl - Główny element Swipera.
 */
const handleExpandableReviews = (swiperInstanceEl) => {
  const reviewSlides = swiperInstanceEl.querySelectorAll('.swiper-slide');

  reviewSlides.forEach(slide => {
    const wrapper = slide.querySelector('.review-content-wrapper');
    if (!wrapper) return;

    const reviewText = wrapper.querySelector('.__txt');
    const moreButton = wrapper.querySelector('.btn-more');

    if (reviewText && moreButton) {
      // Sprawdzamy, czy tekst faktycznie się nie mieści (jest ucięty)
      const isTextOverflowing = reviewText.scrollHeight > reviewText.clientHeight;
      
      if (isTextOverflowing) {
        moreButton.classList.remove('hidden');
        
        // Upewniamy się, że nie dodajemy wielokrotnie tego samego listenera
        if (!moreButton.dataset.listenerAttached) {
          moreButton.addEventListener('click', () => {
            wrapper.classList.add('is-expanded'); // Dodajemy klasę do kontenera
            moreButton.classList.add('hidden'); // Ukrywamy przycisk po kliknięciu
          });
          moreButton.dataset.listenerAttached = 'true';
        }
      } else {
        moreButton.classList.add('hidden');
      }
    }
  });
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
        init: (swiper) => handleExpandableReviews(swiper.el),
        slideChange: (swiper) => handleExpandableReviews(swiper.el),
        resize: (swiper) => handleExpandableReviews(swiper.el),
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
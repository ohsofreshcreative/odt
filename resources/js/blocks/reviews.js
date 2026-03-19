import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

/**
 * Funkcja do obsługi rozwijania tekstu recenzji.
 * @param {HTMLElement} scope - Element nadrzędny, w którym szukamy recenzji (domyślnie cały dokument).
 */
const initReviewMoreButton = (scope = document) => {
  const reviewWrappers = scope.querySelectorAll('.review-content-wrapper');

  reviewWrappers.forEach(wrapper => {
    const textElement = wrapper.querySelector('.__txt');
    const moreButton = wrapper.querySelector('.btn-more');

    if (!textElement || !moreButton) return;

    // Używamy setTimeout, aby dać przeglądarce czas na renderowanie,
    // co jest ważne przy dynamicznym ładowaniu i karuzelach.
    setTimeout(() => {
      // Sprawdzamy, czy tekst jest obcięty (czy jego pełna wysokość jest większa niż widoczna)
      if (textElement.scrollHeight > textElement.clientHeight) {
        moreButton.classList.remove('hidden');
      }
    }, 100); // Niewielkie opóźnienie

    moreButton.addEventListener('click', () => {
      wrapper.classList.add('is-expanded');
      moreButton.classList.add('hidden');
    });
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
      // Dodajemy listener, aby uruchomić logikę przycisku po inicjalizacji Swipera
      on: {
        init: function () {
          initReviewMoreButton(this.el);
        },
        // I po każdej zmianie slajdu (na wypadek pętli)
        slideChange: function () {
            initReviewMoreButton(this.el);
        }
      }
    });
  });
};

// Inicjalizujemy obie funkcje
initReviewsSwiper();
initReviewMoreButton();


// Wsparcie dla podglądu / renderowania bloku w edytorze ACF
if (window.acf) {
  window.acf.addAction('render_block', (el) => {
    const node = el?.[0] ?? el;
    if (node) {
        initReviewsSwiper(node);
        initReviewMoreButton(node);
    }
  });
}

export default initReviewsSwiper;
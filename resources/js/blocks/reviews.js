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
  // Znajdź wszystkie kontenery z recenzjami
  const reviewWrappers = document.querySelectorAll('.review-content-wrapper');

  reviewWrappers.forEach(wrapper => {
    const textElement = wrapper.querySelector('.__txt');
    const moreButton = wrapper.querySelector('.btn-more');

    // Sprawdź, czy tekst faktycznie się "przelewa" (jest dłuższy niż kontener)
    // Porównujemy scrollHeight (całkowita wysokość treści) z clientHeight (widoczna wysokość)
    if (textElement.scrollHeight > textElement.clientHeight) {
      // Jeśli tekst jest obcięty, pokaż przycisk "Zobacz całość"
      moreButton.classList.remove('hidden');
    }

    // Dodaj obsługę kliknięcia na przycisk
    moreButton.addEventListener('click', () => {
      // Dodaj klasę 'is-expanded' do kontenera nadrzędnego
      wrapper.classList.add('is-expanded');
      // Ukryj przycisk "Zobacz całość" po rozwinięciu
      moreButton.classList.add('hidden');
    });
  });
});
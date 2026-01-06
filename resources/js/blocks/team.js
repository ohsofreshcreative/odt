import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const initTeamSwiper = (scope = document) => {
  const swiperElements = scope.querySelectorAll(
    '.team-swiper:not(.swiper-initialized)'
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

// ✅ odpalamy od razu (plik i tak będzie ładowany po warunku w app.js)
initTeamSwiper();

// ✅ ACF preview/editor
if (window.acf) {
  window.acf.addAction('render_block', (el) => {
    const node = el?.[0] ?? el;
    if (node) initTeamSwiper(node);
  });
}

export default initTeamSwiper;

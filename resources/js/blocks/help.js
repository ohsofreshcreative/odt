import Swiper from 'swiper';
import { Navigation, Pagination, Grid } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/grid';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const initHelpSwiper = (scope = document) => {
  const swiperElements = scope.querySelectorAll(
    '.help-swiper:not(.swiper-initialized)'
  );

  if (!swiperElements.length) return;

  swiperElements.forEach((swiperEl) => {
    const nextEl = swiperEl.querySelector('.__next');
    const prevEl = swiperEl.querySelector('.__prev');
    const paginationEl = swiperEl.querySelector('.swiper-pagination');

    new Swiper(swiperEl, {
      modules: [Navigation, Pagination, Grid],

      slidesPerView: 1.2,
      spaceBetween: 30,
      // loop: true, // ❌ Grid nie lubi loop

      grid: {
        rows: 2,
        fill: 'row',
      },

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
          slidesPerView: 2.4,
          grid: {
            rows: 2,
            fill: 'row',
          },
        },
        1024: {
          slidesPerView: 4.2,
          grid: {
            rows: 2,
            fill: 'row',
          },
        },
      },
    });
  });
};

// ✅ odpalamy od razu
initHelpSwiper();

// ✅ ACF preview/editor
if (window.acf) {
  window.acf.addAction('render_block', (el) => {
    const node = el?.[0] ?? el;
    if (node) initHelpSwiper(node);
  });
}

export default initHelpSwiper;

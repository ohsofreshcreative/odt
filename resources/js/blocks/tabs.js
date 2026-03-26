import Swiper from 'swiper';
import { FreeMode } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/free-mode';

const initTabsSwiper = (scope = document) => {
  const swiperElements = scope.querySelectorAll(
    '.tabs-swiper:not(.swiper-initialized)'
  );

  if (!swiperElements.length) return;

  swiperElements.forEach((swiperEl) => {
    const swiper = new Swiper(swiperEl, {
      modules: [FreeMode],
      slidesPerView: 'auto',
      spaceBetween: 0,
      freeMode: { enabled: true, momentum: true },

      // drag myszką + swipe
      simulateTouch: true,
      grabCursor: true,
      allowTouchMove: true,
      touchStartPreventDefault: false,
      threshold: 2,

      // desktop: wyłącz swiper (jeśli tego chcesz)
      breakpoints: {
        768: { enabled: false },
      },
    });

    // Klik taba -> przewiń do niego (tylko gdy swiper jest włączony)
    swiperEl.addEventListener('click', (e) => {
      const el = e.target.closest('[data-tab-index]');
      if (!el) return;
      if (!swiper.enabled) return;

      const index = parseInt(el.dataset.tabIndex, 10);
      if (!Number.isNaN(index)) swiper.slideTo(index, 250);
    });
  });
};

// front
initTabsSwiper();

// ACF preview
if (window.acf) {
  window.acf.addAction('render_block', (el) => {
    const node = el?.[0] ?? el;
    if (node) initTabsSwiper(node);
  });
}

export default initTabsSwiper;
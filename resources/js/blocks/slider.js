import Swiper from 'swiper';
import { Navigation, Pagination, EffectCoverflow } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-coverflow';

const initCoverflowSlider = () => {
  const sliders = document.querySelectorAll('.slider-coverflow');
  if (!sliders.length) {
    return;
  }

  sliders.forEach((slider) => {
    new Swiper(slider, {
      modules: [Navigation, Pagination, EffectCoverflow],
      effect: 'coverflow',
      loop: true,
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: '1',
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true,
      },
      pagination: {
        el: slider.querySelector('.swiper-pagination'),
        clickable: true, 
      },
      breakpoints: {
        320: {
          slidesPerView: 1.5,
        },
        580: {
          slidesPerView: 2,
        },
        767: {
          slidesPerView: 3,
        },
        992: {
          slidesPerView: 3.5,
        },
        1200: {
          slidesPerView: 4,
        },
        1400: {
          slidesPerView: 4.5,
        },
      },
    });
  });
};

initCoverflowSlider();
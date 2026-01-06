import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

const gallerySliders = document.querySelectorAll('.swiper-gallery-places');

if (gallerySliders.length) {
  gallerySliders.forEach((sliderElement) => {
    const nextButton = sliderElement.querySelector('.__next');
    const prevButton = sliderElement.querySelector('.__prev');

    new Swiper(sliderElement, {
      modules: [Navigation],
      loop: true,
      slidesPerView: 1,
      spaceBetween: 10,
      navigation: {
        nextEl: nextButton,
        prevEl: prevButton,
      },
    });
  });
}

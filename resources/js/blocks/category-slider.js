import Swiper from 'swiper';
// import 'swiper/css'; 

const initCategorySlider = () => {
  const swiperContainer = document.querySelector('.category-swiper');
  if (!swiperContainer) {
    return;
  }

  const swiper = new Swiper(swiperContainer, {
    slidesPerView: 'auto',
    spaceBetween: 16,
    // Usuwamy centeredSlides i initialSlide, aby uniknąć konfliktów.
    // Ustawimy pozycję ręcznie.
  });

  // Znajdź aktywny slajd
  const activeLink = swiperContainer.querySelector('.swiper-slide .active');
  if (activeLink) {
    const activeSlide = activeLink.closest('.swiper-slide');
    if (activeSlide) {
      // Pobierz indeks slajdu
      const activeIndex = Array.from(swiper.slides).indexOf(activeSlide);
      
      // Użyj setTimeout, aby dać Swiperowi chwilę na inicjalizację
      // i uniknąć konfliktów z jego wewnętrznymi mechanizmami.
      // Przewijamy do slajdu, centrując go.
      if (activeIndex !== -1) {
        setTimeout(() => {
          swiper.slideTo(activeIndex, 0, false); // Czas animacji 0, bez powiadamiania obserwatorów
          swiper.update(); // Zaktualizuj stan Swipera
        }, 100); // Niewielkie opóźnienie
      }
    }
  }
};

// Uruchom przy ładowaniu strony
if (document.readyState === 'complete') {
  initCategorySlider();
} else {
  document.addEventListener('DOMContentLoaded', initCategorySlider);
}
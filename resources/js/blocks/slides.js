import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const initSlidesSwiper = (scope = document) => {
  const swiperElements = scope.querySelectorAll(
    '.slides-swiper:not(.swiper-initialized)'
  );

  if (!swiperElements.length) return;

  swiperElements.forEach((swiperEl) => {
    const section = swiperEl.closest('.b-slides');
    const nextEl = section?.querySelector('.swiper-button-next-custom');
    const prevEl = section?.querySelector('.swiper-button-prev-custom');
    const progressBar = section?.querySelector('.swiper-progress-bar');

    const swiper = new Swiper(swiperEl, {
      modules: [Navigation, Pagination],

      slidesPerView: 1,
      spaceBetween: 24,
      loop: false,

      navigation: {
        nextEl,
        prevEl,
      },

      breakpoints: {
        768: {
          slidesPerView: 1,
          spaceBetween: 24,
        },
        1024: {
          slidesPerView: 1,
          spaceBetween: 24,
        },
      },

     on: {
        init: function() {
          updateProgressBar(this, progressBar);
        },
        slideChange: function() {
          updateProgressBar(this, progressBar);

          // Reset any playing videos
          const videos = section.querySelectorAll('video');
          videos.forEach(video => {
            const parent = video.parentElement;
            const playButton = parent.querySelector('[data-video-src]');
            
            if (playButton) {
              playButton.style.display = 'flex';
            }
            
            video.remove();
          });
        }
      }
    });
	// Video play logic
    const videoButtons = section.querySelectorAll('[data-video-src]');
    videoButtons.forEach(button => {
      button.addEventListener('click', () => {
        const videoSrc = button.dataset.videoSrc;
        const parent = button.parentElement;

        // Create video element
        const video = document.createElement('video');
        video.src = videoSrc;
        video.controls = true;
        video.autoplay = true;
        video.classList.add('absolute', 'inset-0', 'w-full', 'h-full', 'object-cover', 'radius-img', 'z-10');
        
        // Hide the button
        button.style.display = 'none';

        // Append video to the parent container
        parent.appendChild(video);
      });
    });
  });
};

const updateProgressBar = (swiper, progressBar) => {
  if (!progressBar) return;
  
  const progress = (swiper.activeIndex / (swiper.slides.length - 1)) * 100;
  progressBar.style.width = `${progress}%`;
};

// ✅ Jeśli ten plik jest ładowany lazy z app.js po DOMContentLoaded,
// to możemy zainicjalizować od razu.
initSlidesSwiper();

// ✅ Wsparcie dla podglądu / renderowania bloku w edytorze ACF
if (window.acf) {
  window.acf.addAction('render_block', (el) => {
    // el bywa jQuery-like; bezpiecznie bierzemy pierwszy element DOM
    const node = el?.[0] ?? el;
    if (node) initSlidesSwiper(node);
  });
}

export default initSlidesSwiper;
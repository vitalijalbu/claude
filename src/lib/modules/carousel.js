import Swiper from 'swiper/bundle';
export function initProfileCarousels() {
  document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.swiper.carousel-listings');
    
    if (!carousel) return;
    
    const prevButton = document.querySelector('.carousel-prev');
    const nextButton = document.querySelector('.carousel-next');
    
    const swiper = new Swiper(carousel, {
      slidesPerView: 1,
      spaceBetween: 16,
      breakpoints: {
        640: { slidesPerView: 2, spaceBetween: 20 },
        768: { slidesPerView: 3, spaceBetween: 24 },
        1024: { slidesPerView: 4, spaceBetween: 24 },
        1280: { slidesPerView: 5, spaceBetween: 32 }
      },
      
      // Collegamento bottoni
      navigation: {
        nextEl: nextButton,
        prevEl: prevButton,
      },
      
      // Resto della configurazione...
    });
  });
}
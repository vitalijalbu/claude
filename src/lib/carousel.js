import Swiper from 'swiper/bundle';

var profileThumbs = new Swiper('.gallery-thumbs', {
	loop: true,
	spaceBetween: 10,
	orientation: 'vertical',
	slidesPerView: 4,
	freeMode: true,
	watchSlidesProgress: true,
});

var gallery = new Swiper('.gallery-media', {
	loop: true,
	spaceBetween: 10,
	navigation: {
		nextEl: '.swiper-button-next',
		prevEl: '.swiper-button-prev',
	},
	thumbs: {
		swiper: profileThumbs,
	},
});

var carouselItems = new Swiper('.carousel-listings', {
	spaceBetween: 20,
	slidesPerView: 2,
	breakpoints: {
		640: {
			slidesPerView: 2,
		},
		768: {
			slidesPerView: 2,
		},
		1024: {
			slidesPerView: 4,
		},
	},
	navigation: {
		nextEl: '.carousel-next',
		prevEl: '.carousel-prev',
	},
});

var listingCarousel = new Swiper(".listing-carousel", {
	navigation: {
	  nextEl: ".swiper-button-next",
	  prevEl: ".swiper-button-prev"
	},
	pagination: {
	  el: ".swiper-pagination",
	  dynamicBullets: true,
	},
  });
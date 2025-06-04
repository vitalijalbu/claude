"use client";

import Image from "next/image";
import { Button } from "@heroui/react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination, Autoplay } from "swiper/modules";

const slides = [
  { title: "LOREM IPSUM TITOLO", subtitle: "SOTTOTITOLO" },
  { title: "LOREM IPSUM TITOLO 2", subtitle: "SOTTOTITOLO" },
  { title: "LOREM IPSUM TITOLO 3", subtitle: "SOTTOTITOLO" },
  { title: "LOREM IPSUM TITOLO 4", subtitle: "SOTTOTITOLO" },
];

export function Slideshow() {
  return (
    <>
      <section className="relative h-[500px] lg:h-[700px]">
        <Swiper
          modules={[Navigation, Pagination, Autoplay]}
          spaceBetween={6}
          slidesPerView={1}
          breakpoints={{
            640: {
              slidesPerView: 2,
            }
          }}
          navigation
          pagination={{ clickable: true }}
          autoplay={{ delay: 5000, disableOnInteraction: false }}
          loop
          className="h-full"
        >
          {slides.map((slide, i) => (
            <SwiperSlide key={i}>
              <div className="relative h-full">
                <Image
                  src="/images/placeholder.svg?height=600&width=1200"
                  alt="Kitchen slideshow"
                  fill
                  className="object-cover"
                />
                <div className="absolute inset-0 bg-black/30" />
                <div className="absolute inset-0 flex flex-col items-center justify-center text-center text-white px-6">
                  <h1 className="text-4xl lg:text-6xl font-bold mb-4">
                    {slide.title}
                  </h1>
                  <p className="text-xl lg:text-2xl italic mb-8">
                    {slide.subtitle}
                  </p>
                  <Button radius="full" variant="light">
                    Scopri
                  </Button>
                </div>
              </div>
            </SwiperSlide>
          ))}
        </Swiper>
      </section>

      <section className="bg-[#C17B4A] py-4">
        <div className="max-w-7xl mx-auto px-6 flex items-center justify-between text-white text-sm lg:text-base font-medium">
          <span>PAGA A RATE</span>
          <span>PAGA A RATE</span>
          <span className="hidden sm:block">
            SPEDIZIONE GRATUITA SOPRA I 250€
          </span>
          <span>PAGA A RATE</span>
          <span className="hidden lg:block">PAGA A RATE</span>
        </div>
      </section>
    </>
  );
}

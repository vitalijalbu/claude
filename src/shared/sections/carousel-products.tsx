"use client";
import Image from "next/image";
import Link from "next/link";
import { Heart, ChevronLeft, ChevronRight } from "lucide-react";
import { Button } from "@heroui/react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination } from "swiper/modules";

import { ProductCard } from "../snippets/product-card";
import { PageHeader } from "../components";
import { useRef } from "react";

export function CarouselProducts(props: any) {
  const swiperRef = useRef<any>(null);

  const { title = "Prodotti correlati", products } = props;

  return (
    <section className="container py-6">
      <PageHeader title={title} />
      <div className="relative">
        <Swiper
          spaceBetween={16}
          slidesPerView={2}
          modules={[Navigation, Pagination]}
          navigation={{
            nextEl: ".related-button-next",
            prevEl: ".related-button-prev",
          }}
          pagination={{
            clickable: true,
            el: ".related-pagination",
          }}
          breakpoints={{
            640: {
              slidesPerView: 3,
            },
            1024: {
              slidesPerView: 4,
            },
          }}
          onSwiper={(swiper) => {
            swiperRef.current = swiper;
          }}
          className="related-products-swiper"
        >
          {products?.map((product: any) => (
            <SwiperSlide key={product.id}>
              <ProductCard data={product} />
            </SwiperSlide>
          ))}
        </Swiper>

        <Button
          variant="ghost"
          isIconOnly
          className="related-button-prev absolute left-0 top-1/2 z-10 -translate-x-1/2 -translate-y-1/2 bg-white"
        >
          <ChevronLeft />
          <span className="sr-only">Previous</span>
        </Button>
        <Button
          variant="ghost"
          isIconOnly
          className="related-button-next absolute right-0 top-1/2 z-10 translate-x-1/2 -translate-y-1/2 bg-white"
        >
          <ChevronRight />
          <span className="sr-only">Next</span>
        </Button>
      </div>
    </section>
  );
}

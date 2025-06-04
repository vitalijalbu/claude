"use client";
import { Heart, ChevronLeft, ChevronRight } from "lucide-react";
import { Button } from "@heroui/react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination } from "swiper/modules";
import { ProductCard } from "../snippets/product-card";
import { PageHeader } from "../components";
import { useRef } from "react";

const relatedProducts = [
  {
    id: 1,
    brand: "APEIREM",
    name: "Restore serum",
    price: "24,50€",
    image: "/images/placeholder.svg",
    category: "Viso",
    tag: "",
  },
  {
    id: 2,
    brand: "APEIREM",
    name: "Replenish Cleansing balm",
    price: "19,50€",
    image: "/images/placeholder.svg",
    category: "Viso",
    tag: "",
  },
  {
    id: 3,
    brand: "APEIREM",
    name: "Replenish eye cream",
    price: "19,90€",
    image: "/images/placeholder.svg",
    category: "Viso",
    tag: "IN SCONTO",
  },
  {
    id: 4,
    brand: "APEIREM",
    name: "Calm face moisturiser",
    price: "22,50€",
    image: "/images/placeholder.svg",
    category: "Viso",
    tag: "",
  },
  {
    id: 5,
    brand: "BJORK & BERRIES",
    name: "Hydrating face oil",
    price: "35,00€",
    image: "/images/placeholder.svg",
    category: "Viso",
    tag: "NUOVO",
  },
  {
    id: 6,
    brand: "BJORK & BERRIES",
    name: "Gentle cleansing gel",
    price: "28,50€",
    image: "/images/placeholder.svg",
    category: "Viso",
    tag: "",
  },
];

export function RelatedProducts() {
  const swiperRef = useRef<any>(null);

  return (
    <section className="mt-16">
      <PageHeader title="Altri prodotti viso" />
      <div className="relative">
        <Swiper
          spaceBetween={16}
          slidesPerView={2}
          onSwiper={(swiper) => {
            swiperRef.current = swiper;
          }}
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
          className="related-products-swiper"
        >
          {relatedProducts.map((product) => (
            <SwiperSlide key={product.id}>
              <ProductCard data={product} />
            </SwiperSlide>
          ))}
        </Swiper>

        <Button
          isIconOnly
          className="related-button-prev absolute left-0 top-1/2 z-10 -translate-x-1/2 -translate-y-1/2"
        >
          <ChevronLeft />
          <span className="sr-only">Previous</span>
        </Button>
        <Button
          isIconOnly
          className="related-button-next absolute right-0 top-1/2 z-10 translate-x-1/2 -translate-y-1/2"
        >
          <ChevronRight />
          <span className="sr-only">Next</span>
        </Button>

        <div className="related-pagination mt-4 flex justify-center"></div>
      </div>
    </section>
  );
}

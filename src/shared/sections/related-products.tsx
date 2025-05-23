"use client"
import Image from "next/image"
import Link from "next/link"
import { Heart, ChevronLeft, ChevronRight } from "lucide-react"
import { Button } from "@/components/ui/button"

// Import Swiper and modules
import { Swiper, SwiperSlide } from "swiper/react"
import { Navigation, Pagination } from "swiper/modules"

// Import Swiper styles
import "swiper/css"
import "swiper/css/navigation"
import "swiper/css/pagination"
import { ProductCard } from "../snippets/product-card"

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
]

export function RelatedProducts() {
  return (
    <section className="mt-16">
      <h2 className="mb-8 text-center text-2xl font-light">Altri prodotti viso</h2>
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
          className="related-products-swiper"
        >
          {relatedProducts.map((product) => (
            <SwiperSlide key={product.id}>
                <ProductCard data={product} />
              </SwiperSlide>
          ))}
        </Swiper>

        <Button
          variant="ghost"
          size="icon"
          className="related-button-prev absolute left-0 top-1/2 z-10 h-8 w-8 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white shadow-md"
        >
          <ChevronLeft className="h-4 w-4" />
          <span className="sr-only">Previous</span>
        </Button>
        <Button
          variant="ghost"
          size="icon"
          className="related-button-next absolute right-0 top-1/2 z-10 h-8 w-8 translate-x-1/2 -translate-y-1/2 rounded-full bg-white shadow-md"
        >
          <ChevronRight className="h-4 w-4" />
          <span className="sr-only">Next</span>
        </Button>

        <div className="related-pagination mt-4 flex justify-center"></div>
      </div>
    </section>
  )
}

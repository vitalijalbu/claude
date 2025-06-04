"use client";
import { Card, CardBody, Button, Image } from "@heroui/react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Pagination } from "swiper/modules";
import { useRef } from "react";

interface WishlistItemProps {
  data: {
    id: number;
    date: string;
    articlesCount: number;
    products: { id: number; image: string }[];
  };
}

export default function WishlistItem({ data }: WishlistItemProps) {
  const swiperRef = useRef<any>(null);

  const handleNav = (direction: "prev" | "next") => () => {
    swiperRef.current?.swiper?.[
      direction === "prev" ? "slidePrev" : "slideNext"
    ]();
  };

  return (
    <Card className="w-full border shadow-none cursor-pointer">
      <CardBody
        className="p-6"
        onClick={() => console.log("Wishlist item clicked")}
      >
        <div className="grid grid-cols-1 lg:grid-cols-6 gap-6 items-center">
          <div className="space-y-2 lg:col-span-2">
            <h3 className="text-lg font-semibold text-gray-900">
              Demo wishlist
            </h3>
            <div className="text-sm text-gray-500 space-y-1">
              <p>DATA: {data.date}</p>
              <p>{data.articlesCount} ARTICOLI</p>
            </div>
          </div>
          {/* Product Images Swiper */}
          <div className="relative w-full lg:col-span-4">
            <Swiper
              ref={swiperRef}
              slidesPerView={3}
              spaceBetween={6}
              pagination={{ clickable: true }}
              modules={[Pagination]}
              className="!pb-8"
            >
              {data?.products?.map((product) => (
                <SwiperSlide key={product.id}>
                  <Image
                    src={product.image}
                    alt={`Product ${product.id}`}
                    className="aspect-square object-cover rounded-lg"
                  />
                </SwiperSlide>
              ))}
            </Swiper>

            {data?.products?.length > 3 && (
              <>
                <Button
                  isIconOnly
                  variant="ghost"
                  onPress={handleNav("prev")}
                  className="border-0 absolute -left-4 top-1/2 z-20 -translate-y-1/2"
                >
                  <ChevronLeft />
                </Button>
                <Button
                  isIconOnly
                  variant="light"
                  onPress={handleNav("next")}
                  className="border-0 absolute -right-4 top-1/2 z-20 -translate-y-1/2"
                >
                  <ChevronRight />
                </Button>
              </>
            )}
          </div>
        </div>
      </CardBody>
    </Card>
  );
}

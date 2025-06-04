"use client";
import Link from "next/link";
import { Card, CardBody, Button, Image } from "@heroui/react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Pagination } from "swiper/modules";
import { useRef } from "react";
import { ChevronLeft, ChevronRight } from "lucide-react";

export default function OrderItem(props: any) {
  const { data, index } = props;
  const swiperRef = useRef<any>(null);

  const handleNav = (direction: "prev" | "next") => () => {
    swiperRef.current?.swiper?.[
      direction === "prev" ? "slidePrev" : "slideNext"
    ]();
  };

  return (
    <Card key={index} className="w-full border" shadow="none">
      <CardBody className="p-6">
        <div className="grid grid-cols-1 lg:grid-cols-6 gap-6 items-center">
          <div className="space-y-2 lg:col-span-2">
            <Link href={`/account/orders/${data.id}`}>
              <h3 className="text-lg font-semibold text-gray-900">
                Ordine numero {data.id}
              </h3>
              <div className="text-sm text-gray-500 space-y-1">
                <p>DATA: {data.date}</p>
                <p>{data.articlesCount} ARTICOLI</p>
              </div>
              <div className="text-2xl font-bold text-gray-900 mt-3">
                {data.total}
              </div>
              <Button variant="bordered" color="primary" className="mt-3">
                {data.status}
              </Button>
            </Link>
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

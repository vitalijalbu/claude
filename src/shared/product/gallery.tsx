"use client";
import { useState } from "react";
import Image from "next/image";
import { Heart, X, ChevronLeft, ChevronRight } from "lucide-react";
import {
  Chip,
  Modal,
  ModalContent,
  ModalHeader,
  ModalBody,
  ModalFooter,
  Button,
  Checkbox,
  useDisclosure,
} from "@heroui/react";

// Import Swiper and modules
import { Swiper, SwiperSlide } from "swiper/react";
import { FreeMode, Navigation, Thumbs, Zoom } from "swiper/modules";
import IconHeart from "@/assets/icons/heart";
import { FavoriteButton } from "./favorite-button";

const productImages = [
  {
    id: 1,
    src: "/images/placeholder.svg?height=600&width=600&text=Product+Image+1",
    alt: "Acqua Spray Idratante - Front view",
  },
  {
    id: 2,
    src: "/images/placeholder.svg?height=600&width=600&text=Product+Image+2",
    alt: "Acqua Spray Idratante - Side view",
  },
  {
    id: 3,
    src: "/images/placeholder.svg?height=600&width=600&text=Product+Image+3",
    alt: "Acqua Spray Idratante - Ingredients",
  },
  {
    id: 4,
    src: "/images/placeholder.svg?height=600&width=600&text=Product+Image+4",
    alt: "Acqua Spray Idratante - In use",
  },
];

export function ProductGallery() {
  const [thumbsSwiper, setThumbsSwiper] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [activeIndex, setActiveIndex] = useState(0);
  const { isOpen, onOpen, onOpenChange } = useDisclosure();

  return (
    <div className="space-y-4">
      <div className="relative">
        <Chip className="absolute left-3 bg-black text-white top-3 z-10">
          ESAURITO
        </Chip>
        <FavoriteButton />

        <Swiper
          spaceBetween={10}
          navigation={{
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
          }}
          thumbs={{
            swiper:
              thumbsSwiper && !thumbsSwiper.destroyed ? thumbsSwiper : null,
          }}
          modules={[FreeMode, Navigation, Thumbs, Zoom]}
          zoom={true}
          className="aspect-square rounded-md bg-gray-100"
          onSlideChange={(swiper) => setActiveIndex(swiper.activeIndex)}
        >
          {productImages.map((image) => (
            <SwiperSlide
              key={image.id}
              className="cursor-pointer"
              onClick={() => setIsModalOpen(true)}
            >
              <div className="swiper-zoom-container relative h-full w-full">
                <div className="relative h-full w-full">
                  <Image
                    src={"/images/placeholder.svg"}
                    alt={image.alt}
                    fill
                    className="object-cover"
                  />
                </div>
              </div>
            </SwiperSlide>
          ))}
          <div className="swiper-button-prev !left-2 !h-8 !w-8 rounded-full bg-white/80 !text-gray-800 after:!text-sm"></div>
          <div className="swiper-button-next !right-2 !h-8 !w-8 rounded-full bg-white/80 !text-gray-800 after:!text-sm"></div>
        </Swiper>
      </div>

      <Swiper
        onSwiper={setThumbsSwiper}
        spaceBetween={10}
        slidesPerView={4}
        freeMode={true}
        watchSlidesProgress={true}
        modules={[FreeMode, Navigation, Thumbs]}
        className="thumbnails-swiper"
      >
        {productImages.map((image) => (
          <SwiperSlide
            key={image.id}
            className="cursor-pointer rounded-md border border-gray-200 p-1 hover:border-gray-300"
          >
            <div className="relative aspect-square overflow-hidden rounded-sm bg-gray-100">
              <Image
                src={"/images/placeholder.svg"}
                alt={image.alt}
                fill
                className="object-cover"
              />
            </div>
          </SwiperSlide>
        ))}
      </Swiper>

      <Modal isOpen={isOpen} onOpenChange={onOpenChange}>
        <ModalBody className="w-full h-full border-none bg-black/95 p-0 text-white sm:rounded-lg">
          <div className="relative h-screen max-h-[80vh] w-full">
            <Button
              variant="ghost"
              isIconOnly
              className="absolute right-4 top-4 z-50 h-10 w-10 rounded-full bg-black/50 text-white hover:bg-black/70"
              onPress={() => setIsModalOpen(false)}
            >
              <X className="h-6 w-6" />
              <span className="sr-only">Close</span>
            </Button>

            <Swiper
              initialSlide={activeIndex}
              spaceBetween={0}
              navigation={{
                nextEl: ".modal-button-next",
                prevEl: ".modal-button-prev",
              }}
              modules={[Navigation, Zoom]}
              zoom={{
                maxRatio: 3,
              }}
              className="h-full w-full"
            >
              {productImages.map((image) => (
                <SwiperSlide
                  key={image.id}
                  className="flex items-center justify-center"
                >
                  <div className="swiper-zoom-container relative h-full w-full">
                    <div className="relative h-full w-full">
                      <Image
                        src={"/images/placeholder.svg"}
                        alt={image.alt}
                        fill
                        className="object-contain"
                      />
                    </div>
                  </div>
                </SwiperSlide>
              ))}
            </Swiper>

            <Button
              variant="ghost"
              isIconOnly
              className="modal-button-prev absolute left-4 top-1/2 z-10 h-12 w-12 -translate-y-1/2 rounded-full bg-black/50 text-white hover:bg-black/70"
            >
              <ChevronLeft className="h-8 w-8" />
              <span className="sr-only">Previous</span>
            </Button>
            <Button
              variant="ghost"
              isIconOnly
              className="modal-button-next absolute right-4 top-1/2 z-10 h-12 w-12 -translate-y-1/2 rounded-full bg-black/50 text-white hover:bg-black/70"
            >
              <ChevronRight className="h-8 w-8" />
              <span className="sr-only">Next</span>
            </Button>
          </div>

          <div className="bg-black p-4">
            <Swiper
              spaceBetween={10}
              slidesPerView={6}
              modules={[FreeMode, Navigation]}
              className="thumbnails-modal-swiper"
              breakpoints={{
                320: {
                  slidesPerView: 3,
                },
                640: {
                  slidesPerView: 4,
                },
                768: {
                  slidesPerView: 5,
                },
                1024: {
                  slidesPerView: 6,
                },
              }}
            >
              {productImages.map((image, index) => (
                <SwiperSlide
                  key={image.id}
                  className={`cursor-pointer rounded-md border p-1 ${index === activeIndex ? "border-white" : "border-gray-700"}`}
                  onClick={() => {
                    const modalSwiper =
                      document.querySelector(".modal-swiper")?.swiper;
                    if (modalSwiper) {
                      modalSwiper.slideTo(index);
                    }
                  }}
                >
                  <div className="relative aspect-square overflow-hidden rounded-sm bg-gray-800">
                    <Image
                      src={"/images/placeholder.svg"}
                      alt={image.alt}
                      fill
                      className="object-cover"
                    />
                  </div>
                </SwiperSlide>
              ))}
            </Swiper>
          </div>
        </ModalBody>
      </Modal>
    </div>
  );
}

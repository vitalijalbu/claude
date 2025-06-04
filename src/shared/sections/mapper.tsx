import { Suspense } from "react";
import { Spinner } from "@heroui/react";
import { Slideshow } from "./slideshow";
import { Hero } from "./hero";
import { CategoryHero } from "./category-hero";
import { CarouselProducts } from "./carousel-products";
import { MediaText } from "./media-text";

const SectionMapper: React.FC<{ data: any[] }> = ({ data }) => {
  return (
    <Suspense fallback={<Spinner />}>
      {data.map((section, index) => {
        switch (section.type) {
          case "slideshows":
            return <Slideshow key={index} data={section} />;
          case "hero":
            return <Hero key={index} data={section} />;
          case "media_text":
            return <MediaText key={index} data={section} />;
          case "category_hero":
            return <CategoryHero key={index} data={section} />;
          case "products_carousel":
            return <CarouselProducts key={index} data={section} />;
          default:
            return null;
        }
      })}
    </Suspense>
  );
};

export default SectionMapper;

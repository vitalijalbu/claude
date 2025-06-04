"use client";
import Link from "next/link";
import { ProductCard } from "@/shared/snippets/product-card";
import { useList } from "@/hooks";
import { CategoryHero } from "@/shared/sections/category-hero";
import { Hero } from "@/shared/sections/hero";
import { CarouselProducts } from "@/shared/sections/carousel-products";
import { Slideshow } from "@/shared/sections/slideshow";
import { GridCategories } from "@/shared/sections/grid-categories";

export default function Home() {
  const { data, isLoading, error } = useList("products");
  return (
    <div className="flex min-h-screen flex-col">
      <Slideshow />
      <GridCategories />
      <CategoryHero />
      <Hero />
      <CarouselProducts
        title="Demo section"
        products={data?.products}
        isLoading={isLoading}
        error={error}
      />
      <CategoryHero />
      <Hero
        title="demo title"
        subtitle="demo subtitle"
        primaryAction="Scopri"
        primaryActionUrl={"/categoria-prodotto/demo"}
      />
      <CarouselProducts
        title="Demo section"
        products={data?.products}
        isLoading={isLoading}
        error={error}
      />
    </div>
  );
}

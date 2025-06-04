"use client";
import Link from "next/link";
import { ProductCard } from "@/shared/snippets/product-card";
import { useList } from "@/hooks";
import { Hero } from "@/shared/sections/hero";
import { CategoryFilters } from "@/shared/search/filters";
import { BreadcrumbItem, Breadcrumbs } from "@heroui/react";
import { ChevronLeft, Filter } from "lucide-react";
import { OrderBy } from "@/shared/search/order-by";

const data = [
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

export default function Page() {
  return (
    <>
      <Hero title="demo categoria" />
      <div className="container">
        <div className="flex items-center justify-between py-4">
          <Breadcrumbs>
            <BreadcrumbItem as={Link} href={`/categoria-prodotto/demo`}>
              Fragrance and Beauty
            </BreadcrumbItem>
            <BreadcrumbItem>Viso</BreadcrumbItem>
          </Breadcrumbs>
          <OrderBy />
        </div>
        <CategoryFilters />
        <div className="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 md:grid-cols-4">
          {data?.map((product) => (
            <ProductCard data={product} key={product.id} />
          ))}
        </div>
      </div>
    </>
  );
}

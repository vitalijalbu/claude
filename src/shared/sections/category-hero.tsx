import Image from "next/image";
import { Button, Card, CardBody } from "@heroui/react";
import { Heart, ChevronRight } from "lucide-react";
import { ProductCard } from "../snippets/product-card";

const products = [
  {
    id: 1,
    image: "/placeholder.svg?height=200&width=200",
    alt: "Tropical bedding set",
    category: "BLIM",
    name: "Prodotto",
    subtitle: "Prodotto",
    price: "00,00€",
    colors: ["bg-orange-500", "bg-red-500"],
  },
  {
    id: 2,
    image: "/placeholder.svg?height=200&width=200",
    alt: "Gray bedding set",
    category: "BLIM",
    name: "Prodotto",
    subtitle: "Prodotto",
    price: "00,00€",
    colors: ["bg-orange-500", "bg-red-500"],
  },
  {
    id: 3,
    image: "/placeholder.svg?height=200&width=200",
    alt: "White bedding set",
    category: "BLIM",
    name: "Prodotto",
    subtitle: "Prodotto",
    price: "00,00€",
    colors: ["bg-red-500", "bg-red-600"],
  },
];

export function CategoryHero() {
  return (
    <section className="bg-[#C17B4A] px-6 py-12 lg:px-12">
      <div className="max-w-7xl mx-auto">
        <div className="mb-8">
          <h1 className="text-white text-4xl lg:text-5xl font-bold mb-2">
            COPRIPIUMINI COLORATI
          </h1>
          <p className="text-white text-lg lg:text-xl italic">
            Rinnova il tuo letto con eleganza
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
          {/* Main bedroom image */}
          <div className="lg:col-span-2">
            <div className="relative aspect-[4/3] rounded-lg overflow-hidden">
              <Image
                src="/images/placeholder.svg"
                alt="Bedroom with patterned duvet cover"
                fill
                className="object-cover"
              />
            </div>
          </div>

          {/* Product cards */}
          <div className="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {products.map((product) => (
              <ProductCard
                key={product.id}
                data={{
                  id: product.id,
                  alt: product.alt,
                  title: product.name,
                  description: product.subtitle,
                  price: product.price,
                  tag: "Nuovo",
                  tagColor: "green",
                }}
              />
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

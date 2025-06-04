"use client";
import Image from "next/image";
import Link from "next/link";
import { Heart, Share2, Star } from "lucide-react";

import {
  Badge,
  Breadcrumbs,
  BreadcrumbItem,
  Button,
  Checkbox,
  Accordion,
  AccordionItem,
} from "@heroui/react";

import { RelatedProducts } from "@/shared/sections/related-products";
import { ProductGallery } from "@/shared/product/gallery";

export default function Page() {
  return (
    <main className="flex-1">
      <div className="container py-6">
        <Breadcrumbs>
          <BreadcrumbItem href="/">
            Home
          </BreadcrumbItem>
          <BreadcrumbItem href={`/categoria-prodotto/demo`}>
            Categoria
          </BreadcrumbItem>
          <BreadcrumbItem href={`/categoria-prodotto/demo/demo`}>
            Sotto categoria
          </BreadcrumbItem>
          <BreadcrumbItem>Demo prodotto</BreadcrumbItem>
        </Breadcrumbs>

        <div className="mt-4 rounded-md bg-gray-100 p-4 text-sm">
          <p>
            Al momento il prodotto esaurito! Aggiungilo ai preferiti e clicca il
            bottone &quot;Inviami una Notifica&quot;, provvederemo a inviarti
            una mail quando sarà nuovamente disponibile.
          </p>
        </div>

        <div className="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-2">
          {/* Product Images */}
          <div className="space-y-4">
            <div className="relative aspect-square overflow-hidden rounded-md bg-gray-100">
              <ProductGallery />
            </div>
            <div className="grid grid-cols-4 gap-2">
              {[1, 2].map((i) => (
                <button
                  key={i}
                  className="aspect-square rounded-md border border-gray-200 bg-white p-1 hover:border-gray-300"
                >
                  <div className="relative aspect-square overflow-hidden rounded-sm bg-gray-100">
                    <Image
                      src="/images/placeholder.svg?height=100&width=100"
                      alt={`Product thumbnail ${i}`}
                      fill
                      className="object-cover"
                    />
                  </div>
                </button>
              ))}
            </div>
          </div>

          {/* Product Details */}
          <div className="space-y-6">
            <div className="flex items-center justify-between">
              <Link
                href="#"
                className="text-sm font-medium uppercase hover:underline"
              >
                BJORK & BERRIES
              </Link>
              <Button variant="ghost" isIconOnly className="h-8 w-8">
                <Share2 />
                <span className="sr-only">Share</span>
              </Button>
            </div>

            <h1 className="text-3xl font-light">
              Acqua Spray Idratante - Deep forest face mist
            </h1>
            <p className="text-2xl font-medium">20,40 €</p>

            <div className="flex gap-2">
              {["#9ca3af", "#06b6d4", "#22c55e"].map((color) => (
                <button
                  key={color}
                  className="h-8 w-8 rounded-md border border-gray-300"
                  style={{ backgroundColor: color }}
                  aria-label={`Select color ${color}`}
                />
              ))}
            </div>

            <div className="space-y-4 border-t pt-6">
              <h2 className="font-medium">Descrizione</h2>
              <p className="text-sm text-gray-600">
                Idrata e rinfresca il tuo viso con questo spray, una miscela
                rinfrescante di acqua di foglie di betulla biologica e acqua a
                base di frutti pieni di vitamine. Ideale da usare quando si sta
                davanti al computer, in condizioni climatiche o asciutte, quando
                si viaggia o quando la pelle ha bisogno di una carica
                d&apos;energia.
              </p>
            </div>

            <Accordion>
              <AccordionItem
                key="info"
                aria-label="Maggiori Informazioni"
                title="Maggiori Informazioni"
              >
                <>
                  <div className="text-sm text-gray-600">
                    <p>
                      Ingredienti: Aqua, Betula Alba Leaf Water*, Pyrus Malus
                      Fruit Water*, Glycerin, Sodium Benzoate, Potassium
                      Sorbate, Citric Acid, Parfum, Limonene.
                    </p>
                    <p className="mt-2">
                      *Ingredienti da agricoltura biologica
                    </p>
                    <p className="mt-2">Formato: 100ml</p>
                  </div>
                </>
              </AccordionItem>
              <AccordionItem
                key="features"
                aria-label="Caratteristiche"
                title="Caratteristiche"
              >
                <>
                  <div className="text-sm text-gray-600">
                    <ul className="list-inside list-disc space-y-1">
                      <li>100% ingredienti naturali</li>
                      <li>Senza parabeni</li>
                      <li>Senza siliconi</li>
                      <li>Non testato su animali</li>
                      <li>Prodotto in Svezia</li>
                    </ul>
                  </div>
                </>
              </AccordionItem>
            </Accordion>

            <div className="rounded-md border p-4">
              <div className="flex items-center gap-3">
                <Checkbox className="flex-1 text-sm font-medium">
                  OPZIONE REGALO
                </Checkbox>
              </div>
              <p className="mt-2 pl-9 text-sm text-gray-600">
                Questo prodotto è un regalo{" "}
                <span className="font-medium">+ 4,50 €</span>
              </p>
            </div>

            <Button className="w-full" color="primary">
              Acquista
            </Button>

            <p className="text-center text-sm text-gray-600">
              TI MANCANO 120,00 EURO PER RAGGIUNGERE LA SPEDIZIONE GRATUITA!
            </p>

            <div className="flex flex-wrap justify-center gap-4 border-t pt-4">
              {[
                "mastercard",
                "maestro",
                "paypal",
                "apple-pay",
                "google-pay",
                "visa",
              ].map((payment) => (
                <div key={payment} className="h-8 w-12 opacity-70">
                  <div className="relative h-full w-full">
                    <Image
                      src={`/images/placeholder.svg?height=32&width=48&text=${payment}`}
                      alt={payment}
                      fill
                      className="object-contain"
                    />
                  </div>
                </div>
              ))}
            </div>

            <div className="flex items-center justify-center gap-1 border-t pt-4">
              {[1, 2, 3, 4, 5].map((star) => (
                <Star
                  key={star}
                  className="h-4 w-4 fill-current text-yellow-400"
                />
              ))}
              <Link
                href="#"
                className="ml-2 text-sm text-gray-600 hover:underline"
              >
                Vedi recensioni
              </Link>
            </div>
          </div>
        </div>

        <RelatedProducts />
      </div>
    </main>
  );
}

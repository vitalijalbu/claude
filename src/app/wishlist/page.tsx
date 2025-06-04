"use client";
import Link from "next/link";
import { ProductCard } from "@/shared/snippets/product-card";
import { useGet, useList } from "@/hooks";
import { PageHeader } from "@/shared/components";

export default function Home() {
  const { data, isLoading, error } = useGet("wishlist", 1);

  return (
    <div className="container">
      <PageHeader title={"Wishlist"} subtitle="I tuoi prodotti preferiti" />
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {data?.products?.map((product: any) => (
          <Link key={product.id} href={`/prodotto/${product.slug}`}>
            <ProductCard data={product} />
          </Link>
        ))}
        {isLoading && <p>Loading...</p>}
        {error && <p>Error loading products</p>}
      </div>
    </div>
  );
}

"use client";
import Link from "next/link";
import { ProductCard } from "@/shared/snippets/product-card";
import { useList } from "@/hooks";
import ModalWishlist from "@/shared/account/modal-wishlist";
import WishlistItem from "@/shared/snippets/wishlist-item";

export default function Page() {
  //const { data, isLoading, error } = useList("products");

  const data = [
    {
      id: "1",
      date: "21/02/2025",
      articlesCount: 3,
      total: "250,00€",
      status: "ACQUISTA DI NUOVO",
      products: [
        { id: 1, image: "/images/placeholder.svg" },
        { id: 2, image: "/images/placeholder.svg" },
        { id: 3, image: "/images/placeholder.svg" },
        { id: 4, image: "/images/placeholder.svg" },
        { id: 5, image: "/images/placeholder.svg" },
      ],
    },
    {
      id: "2",
      date: "21/02/2025",
      articlesCount: 6,
      total: "250,00€",
      status: "ACQUISTA DI NUOVO",
      products: [
        { id: 1, image: "/images/placeholder.svg" },
        { id: 2, image: "/images/placeholder.svg" },
        { id: 3, image: "/images/placeholder.svg" },
        { id: 4, image: "/images/placeholder.svg" },
        { id: 5, image: "/images/placeholder.svg" },
      ],
    },
    {
      id: "3",
      date: "21/02/2025",
      articlesCount: 6,
      total: "250,00€",
      status: "ACQUISTA DI NUOVO",
      products: [
        { id: 1, image: "/images/placeholder.svg" },
        { id: 2, image: "/images/placeholder.svg" },
        { id: 3, image: "/images/placeholder.svg" },
        { id: 4, image: "/images/placeholder.svg" },
        { id: 5, image: "/images/placeholder.svg" },
      ],
    },
  ];

  return (
    <div className="flex min-h-screen flex-col">
      <ModalWishlist />
      <div className="flex flex-col gap-6 my-6">
        {data?.map((item) => <WishlistItem data={item} key={item.id} />)}
      </div>
    </div>
  );
}

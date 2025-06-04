"use client";
import { useList } from "@/hooks";
import { PageHeader } from "@/shared/components";
import OrderItem from "@/shared/snippets/order-item";

const orders = [
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
      { id: 6, image: "/images/placeholder.svg" },
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
    ],
  },
];

export default function Page() {
  return (
    <div className="">
      <PageHeader title="I miei ordini" />
      <div className="flex flex-col gap-6">
        {orders.map((order, index) => (
          <OrderItem key={index} data={order} index={index} />
        ))}
      </div>
    </div>
  );
}

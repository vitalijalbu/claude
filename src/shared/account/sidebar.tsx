"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { Card, CardBody } from "@heroui/react";

export function AccountSidebar() {
  const pathname = usePathname();

  const items = [
    { key: "info", label: "Info", href: "/account", exact: true },
    { key: "orders", label: "Ordini", href: "/account/orders" },
    { key: "fidelity", label: "Fidelity card", href: "/account/fidelity" },
    { key: "wishlist", label: "Wishlist", href: "/account/wishlist" },
  ];

  const isActive = (item: (typeof items)[0]) =>
    item.exact ? pathname === item.href : pathname.startsWith(item.href);

  return (
    <div>
      <h3 className="text-2xl uppercase mb-2 font-semibold">Profilo</h3>
      <div className="flex flex-col gap-2">
        {items.map((item) => (
          <Card
            as={Link}
            href={item.href}
            key={item.key}
            shadow="none"
            className={`w-full border shadow-none ${
              isActive(item) ? "bg-zinc-800 text-white" : null
            }`}
          >
            <CardBody>{item.label}</CardBody>
          </Card>
        ))}
      </div>
    </div>
  );
}

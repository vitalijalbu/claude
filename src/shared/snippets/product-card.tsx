import Image from "next/image";
import Link from "next/link";
import { cn } from "@/lib/utils";
import { Card, CardBody, Chip } from "@heroui/react";
import { FavoriteButton } from "../product/favorite-button";

export function ProductCard({ data }: any) {
  return (
    <Card shadow="none" className="border" key={data?.id}>
      <div className="relative mb-4 aspect-square overflow-hidden rounded-t-md bg-gray-100">
        <Link href={`/prodotto/${data?.id}`}>
          <Image
            src={data.thumbnail ? data?.thumbnail : "/images/placeholder.svg"}
            width={300}
            height={300}
            objectFit="cover"
            loading="lazy"
            alt={`${data?.name} ${data?.description}`}
            className="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105"
          />
        </Link>
        <FavoriteButton />
        {data?.tag && (
          <Chip
            size="sm"
            className={cn(
              "absolute left-3 top-3 px-2 py-1 text-xs font-medium uppercase text-white",
            )}
          >
            {data?.tag}
          </Chip>
        )}
      </div>
      <CardBody className="flex flex-col">
        <Link href={`/prodotto/${data?.id}`}>
          <span className="block text-muted-foreground mb-1">Viso</span>
          <span className="block text-sm font-medium uppercase hover:underline mb-2">
            BJORK & BERRIES
          </span>

          <span className="block mb-4 text-xl hover:underline">{data?.name}</span>
          <p className="mt-1 font-medium">{data?.price}</p>
        </Link>
      </CardBody>
    </Card>
  );
}

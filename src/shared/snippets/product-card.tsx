import Image from "next/image"
import Link from "next/link"
import { Heart } from "lucide-react"

import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "../components/ui/card"


export function ProductCard({data}) {
  return (
    <Card className="group relative flex flex-col p-0" key={data?.id}>
      <div className="relative mb-4 aspect-square overflow-hidden rounded-sm bg-gray-100">
        <Image
          src={"/images/placeholder.svg"}
          alt={`${data?.name} ${data?.description}`}
          fill
          className="object-cover transition-transform duration-300 group-hover:scale-105"
        />
        <Button
          variant="ghost"
          size="icon"
          className="absolute right-2 top-2 h-8 w-8 rounded-full bg-white/80 text-gray-700 opacity-0 transition-opacity group-hover:opacity-100"
        >
          <Heart className="h-4 w-4" />
          <span className="sr-only">Add to wishlist</span>
        </Button>
        {data?.tag && (
          <div
            className={cn(
              "absolute left-0 top-3 px-2 py-1 text-xs font-medium uppercase text-white",
              data?.tagColor === "green" && "bg-green-600",
              data?.tagColor === "orange" && "bg-orange-500",
              data?.tagColor === "black" && "bg-black",
            )}
          >
            {data?.tag}
          </div>
        )}
      </div>
      <CardContent className="flex flex-col">
        <Link href="/prodotti/demo" className="text-sm font-medium uppercase hover:underline">
          {data?.name}
        </Link>
        <p className="text-sm text-gray-600">{data?.description}</p>
        <p className="mt-1 font-medium">{data?.price}</p>
      </CardContent>
    </Card>
  )
}

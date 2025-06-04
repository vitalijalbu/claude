import React from "react";
import Image from "next/image";
import { Button } from "@heroui/react";
import Link from "next/link";

export function Hero(props: any) {
  const {
    className,
    title,
    subtitle,
    primaryAction,
    primaryActionUrl,
    imageUrl,
  } = props;
  return (
    <section className="relative h-[400px] lg:h-[500px]">
      <div className="absolute inset-0">
        <Image
          src={imageUrl || "/images/placeholder.svg"}
          alt={title || "Hero Image"}
          fill
          className="object-cover"
        />
        <div className="absolute inset-0 bg-black/20"></div>
      </div>

      <div className="relative z-10 flex flex-col items-center justify-center h-full text-center px-6">
        <h2 className="text-white text-4xl lg:text-5xl font-bold mb-4">
          {title}
        </h2>
        {subtitle && (
          <p className="text-white text-lg lg:text-xl italic mb-8">
            {subtitle}
          </p>
        )}
        {primaryAction && (
          <Button as={Link} href={primaryActionUrl} className="bg-white">
            Scopri
          </Button>
        )}
      </div>
    </section>
  );
}

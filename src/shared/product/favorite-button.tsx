"use client";

import IconHeart from "@/assets/icons/heart";
import { Button } from "@heroui/react";
import React from "react";

export function FavoriteButton() {
  return (
    <Button
      color="default"
      variant="light"
      isIconOnly
      radius="full"
      className="absolute bg-white right-2 top-2"
    >
      <IconHeart color="#111"/>
      <span className="sr-only">Add to wishlist</span>
    </Button>
  );
}

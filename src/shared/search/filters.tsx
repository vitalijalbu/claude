import React from "react";
import { Button } from "@heroui/react";
import SelectBrand from "./select-brand";

export const CategoryFilters = (props: any) => {
  return (
    <section className="container py-6">
      <div className="flex gap-4">
        <Button variant="bordered">Creme solari</Button>
        <Button variant="bordered">Creme viso</Button>
        <SelectBrand />
      </div>
    </section>
  );
};

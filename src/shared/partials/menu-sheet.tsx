"use client";

import Link from "next/link";
import { Menu, ChevronLeft, ArrowLeft } from "lucide-react";
import { useState } from "react";
import Image from "next/image";
import {
  Drawer,
  DrawerContent,
  DrawerHeader,
  DrawerBody,
  Accordion,
  AccordionItem,
  useDisclosure,
} from "@heroui/react";

export function MenuSheet(props: any) {
  const { categories } = props;
  const { isOpen, onOpen, onOpenChange } = useDisclosure();
  const [selectedCategory, setSelectedCategory] = useState<any>(null);

  const handleCategoryClick = (category: any) => {
    setSelectedCategory(category);
  };

  const handleBackToMain = () => {
    setSelectedCategory(null);
  };

  const handleDrawerClose = () => {
    setSelectedCategory(null);
    onOpenChange();
  };

  return (
    <>
      <button
        title="Open menu"
        onClick={onOpen}
        className="flex flex-col items-center text-white"
      >
        <Menu size={24} />
      </button>

      <Drawer 
        isOpen={isOpen} 
        onOpenChange={handleDrawerClose} 
        placement="left" 
        radius="none"
        size="sm"
        classNames={{
          base: "text-white bg-[#5a5a3a]",
          backdrop: "bg-black/50",
        }}
      >
        <DrawerContent className="bg-[#5a5a3a]">
          {!selectedCategory ? (
            // Main Menu View
            <>
              <DrawerHeader className="flex justify-center items-center py-6 border-b border-white/20">
                <Image
                  src="/images/logo-light.svg"
                  alt="STILE STORE"
                  width={120}
                  height={40}
                  className="h-8"
                />
              </DrawerHeader>
              <DrawerBody className="p-0">
                <div className="flex flex-col">
                  {categories.map((category: any, index: number) => (
                    <button
                      key={index}
                      onClick={() => handleCategoryClick(category)}
                      className="flex items-center gap-4 px-6 py-4 text-white hover:bg-white/10 transition-colors border-b border-white/10"
                    >
                      <div className="flex h-8 w-8 items-center justify-center">
                        <Image
                          src={`/images/icons/ICONE_shop.svg`}
                          alt={category.title}
                          width={24}
                          height={24}
                          className="text-white"
                        />
                      </div>
                      <span className="text-sm font-medium uppercase">{category.title}</span>
                    </button>
                  ))}
                </div>
              </DrawerBody>
            </>
          ) : (
            // Category Detail View
            <>
              <DrawerHeader className="flex items-center gap-4 py-4 px-6 border-b border-white/20">
                <button
                  onClick={handleBackToMain}
                  className="text-white hover:bg-white/10 p-2 rounded-full transition-colors"
                >
                  <ArrowLeft size={20} />
                </button>
                <div className="flex items-center gap-3">
                  <Image
                    src={`/images/icons/ICONE_shop.svg`}
                    alt={selectedCategory.title}
                    width={24}
                    height={24}
                    className="text-white"
                  />
                  <h2 className="text-lg font-semibold uppercase text-white">
                    {selectedCategory.title}
                  </h2>
                </div>
              </DrawerHeader>
              <DrawerBody className="p-0">
                <div className="flex flex-col">
                  {selectedCategory.subcategories.map((subcategory: any, subIndex: number) => (
                    <Accordion 
                      key={subIndex}
                      selectionMode="single"
                      className="border-b border-white/10"
                      classNames={{
                        base: "text-white",
                        item: "text-white border-none",
                        title: "text-white",
                        content: "text-white",
                        trigger: "px-6 py-4 hover:bg-white/5",
                      }}
                    >
                      <AccordionItem
                        aria-label={subcategory.title}
                        title={
                          <span className="text-sm font-medium uppercase text-white">
                            {subcategory.title}
                          </span>
                        }
                        classNames={{
                          base: "text-white",
                          title: "text-white",
                          content: "text-white px-6 pb-4",
                          trigger: "hover:bg-white/5",
                        }}
                      >
                        <div className="space-y-2">
                          {subcategory.items.map((item: any, itemIndex: number) => (
                            <Link
                              key={itemIndex}
                              href={`/categoria-prodotto/${item.toLowerCase().replace(/\s+/g, "-")}`}
                              className="block py-2 text-sm text-gray-200 hover:text-white transition-colors"
                              onClick={handleDrawerClose}
                            >
                              {item}
                            </Link>
                          ))}
                        </div>
                      </AccordionItem>
                    </Accordion>
                  ))}
                </div>
              </DrawerBody>
            </>
          )}
        </DrawerContent>
      </Drawer>
    </>
  );
}
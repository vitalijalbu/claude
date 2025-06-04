"use client";
import React, { useState } from "react";
import Link from "next/link";
import {
  Search,
  Grid3X3,
  Tag,
  Sparkles,
  Utensils,
  Flower,
  Trees,
  Star,
} from "lucide-react";
import Image from "next/image";
import { Input } from "@heroui/react";
import { motion, AnimatePresence } from "framer-motion";
import { MenuSheet } from "./menu-sheet";
import IconSearch from "@/assets/icons/search";
import IconHeart from "@/assets/icons/heart";
import IconCart from "@/assets/icons/cart";
import IconUser from "@/assets/icons/user";

// Categories data
const categories = [
  {
    id: "brand",
    title: "BRAND",
    icon: Grid3X3,
    subcategories: [
      {
        title: "Featured Brands",
        items: [
          "APEIRON",
          "BJORK & BERRIES",
          "COMFORT ZONE",
          "DAVINES",
          "GROWN ALCHEMIST",
        ],
      },
      {
        title: "All Brands",
        items: [
          "A",
          "B",
          "C",
          "D",
          "E",
          "F",
          "G",
          "H",
          "I",
          "J",
          "K",
          "L",
          "M",
        ],
      },
    ],
  },
  {
    id: "saldi",
    title: "SALDI",
    icon: Tag,
    subcategories: [
      {
        title: "Discounts",
        items: ["Up to 30%", "30% - 50%", "Over 50%"],
      },
      {
        title: "Categories on Sale",
        items: ["Skincare", "Haircare", "Body", "Makeup", "Fragrance"],
      },
    ],
  },
  {
    id: "novita",
    title: "NOVITÀ",
    icon: Sparkles,
    subcategories: [
      {
        title: "New Arrivals",
        items: ["This Week", "This Month", "Bestsellers"],
      },
      {
        title: "Collections",
        items: ["Spring 2025", "Summer Essentials", "Limited Editions"],
      },
    ],
  },
  {
    id: "alimentari",
    title: "ALIMENTARI",
    icon: Utensils,
    subcategories: [
      {
        title: "Food Supplements",
        items: ["Vitamins", "Minerals", "Proteins", "Superfoods"],
      },
      {
        title: "Organic Food",
        items: ["Teas", "Snacks", "Oils", "Honey"],
      },
    ],
  },
  {
    id: "ambienti",
    title: "AMBIENTI",
    subcategories: [
      {
        title: "Home",
        items: ["Candles", "Diffusers", "Room Sprays", "Incense"],
      },
      {
        title: "Sustainable Living",
        items: ["Eco-friendly", "Zero Waste", "Biodegradable"],
      },
    ],
  },
  {
    id: "fragranze",
    title: "FRAGRANZE E BELLEZZA",
    icon: Flower,
    subcategories: [
      {
        title: "Fragrance",
        items: ["Perfume", "Eau de Toilette", "Body Mist", "Scented Oils"],
      },
      {
        title: "Beauty",
        items: ["Face", "Body", "Hair", "Makeup"],
      },
      {
        title: "Face",
        items: ["Cleansers", "Moisturizers", "Serums", "Masks", "Eye Care"],
      },
    ],
  },
  {
    id: "giardino",
    title: "GIARDINO",
    icon: Trees,
    subcategories: [
      {
        title: "Garden Care",
        items: ["Plants", "Tools", "Fertilizers", "Pots"],
      },
      {
        title: "Outdoor",
        items: ["Furniture", "Decorations", "Lighting"],
      },
    ],
  },
  {
    id: "pulizia",
    title: "PULIZIA",
    subcategories: [
      {
        title: "Home Cleaning",
        items: ["All-Purpose", "Kitchen", "Bathroom", "Laundry"],
      },
      {
        title: "Eco-Friendly",
        items: ["Biodegradable", "Refillable", "Plastic-Free", "Natural"],
      },
    ],
  },
  {
    id: "selezione",
    title: "SELEZIONE STILE",
    icon: Star,
    subcategories: [
      {
        title: "Top Rated",
        items: ["Skincare", "Haircare", "Body Care", "Makeup", "Fragrance"],
      },
      {
        title: "Most Popular",
        items: ["This Week", "This Month", "All Time"],
      },
    ],
  },
];

// Animation variants
const backdropVariants = {
  hidden: { 
    opacity: 0,
    transition: {
      duration: 0.1
    }
  },
  visible: { 
    opacity: 1,
    transition: {
      duration: 0.15
    }
  }
};

const dropdownVariants = {
  hidden: { 
    opacity: 0,
    y: -100,
    transition: {
      duration: 0.15,
      ease: "easeIn"
    }
  },
  visible: { 
    opacity: 1,
    y: 0,
    transition: {
      duration: 0.1,
      ease: "easeOut"
    }
  }
};

export function SiteHeader() {
  const [activeDropdown, setActiveDropdown] = useState<string | null>(null);

  const toggleDropdown = (categoryId: string) => {
    setActiveDropdown(activeDropdown === categoryId ? null : categoryId);
  };

  const closeDropdown = () => {
    setActiveDropdown(null);
  };

  return (
    <>
      <header className="sticky top-0 z-50 w-full bg-[#5a5a3a]">
        {/* Mobile Header */}
        <div className="block md:hidden">
          {/* Logo centered */}
          <div className="flex justify-center items-center py-2">
            <Link href="/" className="text-white">
              <Image
                src="/images/logo-light.svg"
                alt="STILE STORE"
                width={150}
                height={50}
                className="h-10"
              />
            </Link>
          </div>

          {/* Mobile Navigation Icons */}
          <div className="flex justify-around items-center py-4 border-t border-white/20">
            {/* Mobile Menu Drawer */}
            <MenuSheet categories={categories} />

            <Link href="/account/wishlist" className="flex flex-col items-center text-white">
              <IconHeart size={24} />
            </Link>
            <Link href="#" className="flex flex-col items-center text-white">
              <IconSearch size={24} />
            </Link>
            <Link href="/cart" className="flex flex-col items-center text-white">
              <IconCart size={24} />
            </Link>
            <Link href="/account" className="flex flex-col items-center text-white">
              <IconUser size={24} />
            </Link>
          </div>
        </div>

        {/* Desktop Header */}
        <div className="hidden md:block">
          {/* Top Bar */}
          <div className="flex items-center justify-between px-6 py-4">
            {/* Logo */}
            <Link href="/" className="text-white">
              <Image
                src="/images/logo-light.svg"
                alt="STILE STORE"
                width={150}
                height={50}
                className="h-10"
              />
            </Link>

            {/* Search Bar */}
            <div className="flex-1 max-w-md mx-8">
              <div className="relative">
                <Input
                  startContent={<IconSearch color="#111"/>}
                  type="text"
                  placeholder="Cerca"
                  className="w-full pl-10 pr-4 py-2 rounded-full border-0 focus:outline-none focus:ring-2 focus:ring-white/20"
                />
              </div>
            </div>

            {/* Right Icons */}
            <div className="flex items-center space-x-6 text-white">
              <Link href="/account" className="flex flex-col items-center">
                <IconUser size={20} />
                <span className="text-xs mt-1">PROFILO</span>
              </Link>
              <Link
                href="/account/wishlist"
                className="flex flex-col items-center"
              >
                <IconHeart size={20} />
                <span className="text-xs mt-1">PREFERITI</span>
              </Link>
              <Link href="/cart" className="flex flex-col items-center">
                <IconCart size={20} />
                <span className="text-xs mt-1">CARRELLO</span>
              </Link>
            </div>
          </div>

          {/* Desktop Navigation */}
          <div className="border-t border-white/20">
            <div className="flex justify-center">
              {categories.map((category) => {
                return (
                  <div key={category.id} className="relative">
                    <button
                      onClick={() => toggleDropdown(category.id)}
                      className="cursor-pointer flex flex-col items-center px-4 py-4 text-white hover:bg-white/10 transition-colors"
                    >
                      <Image
                        src="/images/placeholder-icon.svg"
                        alt={category.title}
                        width={24}
                        height={24}
                        className="mb-1"
                      />
                      <span className="text-xs mt-2 text-center leading-tight max-w-20">
                        {category.title}
                      </span>
                    </button>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </header>

      {/* Desktop Fullwidth Dropdown */}
      <AnimatePresence>
        {activeDropdown && (
          <motion.div
            className="hidden md:block fixed top-0 left-0 w-full h-full bg-black/50 z-40"
            onClick={closeDropdown}
            variants={backdropVariants}
            initial="hidden"
            animate="visible"
            exit="hidden"
          >
            <motion.div 
              className="bg-black text-white"
              style={{ marginTop: '180px' }}
              variants={dropdownVariants}
              initial="hidden"
              animate="visible"
              exit="hidden"
            >
              <div className="container mx-auto px-6 py-8">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  {categories
                    .find((cat) => cat.id === activeDropdown)
                    ?.subcategories.map((subcategory) => (
                      <div key={subcategory.title}>
                        <h3 className="text-lg font-medium mb-4 text-white">
                          {subcategory.title}
                        </h3>
                        <ul className="space-y-2">
                          {subcategory.items.map((item) => (
                            <li key={item}>
                              <Link
                                href={`/categoria-prodotto/${item.toLowerCase().replace(/\s+/g, "-")}`}
                                className="block text-gray-300 hover:text-white transition-colors py-1"
                                onClick={closeDropdown}
                              >
                                {item}
                              </Link>
                            </li>
                          ))}
                        </ul>
                      </div>
                    ))}
                </div>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}
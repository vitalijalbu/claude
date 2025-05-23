"use client"
import Link from "next/link"
import { Menu, X } from "lucide-react"
import { Button } from "@/components/ui/button"
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
} from "@/components/ui/navigation-menu"
import { Sheet, SheetContent, SheetTrigger, SheetClose } from "@/components/ui/sheet"
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion"

const categories = [
  {
    title: "BRAND",
    icon: (
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      >
        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
        <line x1="7" y1="7" x2="7.01" y2="7"></line>
      </svg>
    ),
    subcategories: [
      {
        title: "Featured Brands",
        items: ["APEIRON", "BJORK & BERRIES", "COMFORT ZONE", "DAVINES", "GROWN ALCHEMIST"],
      },
      {
        title: "All Brands",
        items: ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M"],
      },
    ],
  },
  {
    title: "SALE",
    icon: (
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      >
        <line x1="19" y1="5" x2="5" y2="19"></line>
        <circle cx="6.5" cy="6.5" r="2.5"></circle>
        <circle cx="17.5" cy="17.5" r="2.5"></circle>
      </svg>
    ),
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
    title: "NOVITÀ",
    icon: (
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      >
        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
      </svg>
    ),
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
    title: "ALIMENTARI",
    icon: (
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      >
        <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
        <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
        <line x1="6" y1="1" x2="6" y2="4"></line>
        <line x1="10" y1="1" x2="10" y2="4"></line>
        <line x1="14" y1="1" x2="14" y2="4"></line>
      </svg>
    ),
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
    title: "AMBIENTE",
    icon: (
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      >
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
      </svg>
    ),
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
    title: "FRAGRANZE E BEAUTY",
    icon: (
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      >
        <path d="M4.27 19.27L7.5 22.5l2-2 2 2 2-2 2 2 2-2 2 2 .73-.73A10 10 0 0 0 4.27 19.27z"></path>
        <path d="M5 12a7 7 0 0 1 7-7c3.53 0 7 2.47 7 7 0 4.47-2.43 8.36-6 10.5"></path>
        <path d="M12 2v2"></path>
        <path d="M5 10H3"></path>
        <path d="M21 10h-2"></path>
        <path d="M12 17v-7"></path>
        <path d="M9 10h6"></path>
      </svg>
    ),
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
    title: "BAMBINI",
    icon: (
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      >
        <path d="M9 18h6"></path>
        <path d="M10 22h4"></path>
        <path d="M12 2v7"></path>
        <path d="M12 9L8 8"></path>
        <path d="M12 9l4-1"></path>
        <path d="M9 14l-3-3 3-3"></path>
        <path d="M15 14l3-3-3-3"></path>
      </svg>
    ),
    subcategories: [
      {
        title: "Baby Care",
        items: ["Bath & Body", "Diapering", "Feeding", "Health"],
      },
      {
        title: "Kids",
        items: ["Skincare", "Hair Care", "Oral Care", "Sun Protection"],
      },
    ],
  },
  {
    title: "PULIZIA",
    icon: (
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      >
        <path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
      </svg>
    ),
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
    title: "BESTSELLER SHOP",
    icon: (
      <svg
        xmlns="http://www.w3.org/2000/svg"
        className="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      >
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
      </svg>
    ),
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
]

export function SiteHeader() {
  return (
    <>
      {/* Desktop Navigation */}
      <div className="hidden border-t lg:block">
        <div className="container">
          <NavigationMenu className="mx-auto">
            <NavigationMenuList className="flex justify-between">
              {categories.map((category) => (
                <NavigationMenuItem key={category.title}>
                  <NavigationMenuTrigger className="flex flex-col items-center gap-1 px-2 py-3 text-xs font-normal">
                    <span className="flex h-6 w-6 items-center justify-center">{category.icon}</span>
                    <span className="text-center">{category.title}</span>
                  </NavigationMenuTrigger>
                  <NavigationMenuContent>
                    <div className="grid w-[400px] gap-3 p-4 md:w-[500px] md:grid-cols-2 lg:w-[600px]">
                      {category.subcategories.map((subcategory) => (
                        <div key={subcategory.title} className="space-y-2">
                          <h3 className="font-medium">{subcategory.title}</h3>
                          <ul className="space-y-1">
                            {subcategory.items.map((item) => (
                              <li key={item}>
                                <NavigationMenuLink asChild>
                                  <Link href="#" className="block text-sm text-muted-foreground hover:text-foreground">
                                    {item}
                                  </Link>
                                </NavigationMenuLink>
                              </li>
                            ))}
                          </ul>
                        </div>
                      ))}
                    </div>
                  </NavigationMenuContent>
                </NavigationMenuItem>
              ))}
            </NavigationMenuList>
          </NavigationMenu>
        </div>
      </div>

      {/* Mobile Navigation */}
      <div className="border-t lg:hidden">
        <div className="container py-2">
          <Sheet>
            <SheetTrigger asChild>
              <Button variant="outline" size="sm" className="w-full justify-between">
                <span>Categories</span>
                <Menu className="h-4 w-4" />
              </Button>
            </SheetTrigger>
            <SheetContent side="left" className="w-[300px] sm:w-[350px]">
              <div className="flex h-full flex-col">
                <div className="flex items-center justify-between border-b py-4">
                  <h2 className="text-lg font-medium">Categories</h2>
                  <SheetClose asChild>
                    <Button variant="ghost" size="icon">
                      <X className="h-4 w-4" />
                      <span className="sr-only">Close</span>
                    </Button>
                  </SheetClose>
                </div>
                <div className="flex-1 overflow-auto py-4">
                  <Accordion type="multiple" className="w-full">
                    {categories.map((category) => (
                      <AccordionItem key={category.title} value={category.title}>
                        <AccordionTrigger className="flex items-center gap-2 py-2 text-sm">
                          <span className="flex h-5 w-5 items-center justify-center">{category.icon}</span>
                          <span>{category.title}</span>
                        </AccordionTrigger>
                        <AccordionContent>
                          <Accordion type="multiple" className="pl-7">
                            {category.subcategories.map((subcategory) => (
                              <AccordionItem key={subcategory.title} value={subcategory.title}>
                                <AccordionTrigger className="py-2 text-sm">{subcategory.title}</AccordionTrigger>
                                <AccordionContent>
                                  <ul className="space-y-1 pl-4">
                                    {subcategory.items.map((item) => (
                                      <li key={item}>
                                        <Link
                                          href="#"
                                          className="block py-1 text-sm text-muted-foreground hover:text-foreground"
                                        >
                                          {item}
                                        </Link>
                                      </li>
                                    ))}
                                  </ul>
                                </AccordionContent>
                              </AccordionItem>
                            ))}
                          </Accordion>
                        </AccordionContent>
                      </AccordionItem>
                    ))}
                  </Accordion>
                </div>
              </div>
            </SheetContent>
          </Sheet>
        </div>
      </div>
    </>
  )
}

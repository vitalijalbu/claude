import { Flower, Sun, Coffee, Leaf, Scissors } from "lucide-react";
import Image from "next/image";
import { PageHeader } from "../components";

const categories = [
  {
    id: 1,
    name: "CASA E CUCINA",
    icon: "/images/icons/ICONE_beauty.svg",
    color: "bg-[#E07A47]",
    textColor: "text-white",
  },
  {
    id: 2,
    name: "DECORAZIONI",
    icon: "/images/icons/ICONE_beauty.svg",
    color: "bg-[#B8834A]",
    textColor: "text-white",
  },
  {
    id: 3,
    name: "PROFUMI E CANDELE",
    icon: "/images/icons/ICONE_beauty.svg",
    color: "bg-[#8B4513]",
    textColor: "text-white",
  },
  {
    id: 4,
    name: "GIARDINO E OUTDOOR",
    icon: "/images/icons/ICONE_beauty.svg",
    color: "bg-[#7A8471]",
    textColor: "text-white",
  },
  {
    id: 5,
    name: "HOBBY E CREATIVITÀ",
    icon: "/images/icons/ICONE_beauty.svg",
    color: "bg-[#5A6B47]",
    textColor: "text-white",
  },
];

export function GridCategories() {
  return (
    <section className="py-16 px-6 lg:px-12 bg-white">
      <div className="max-w-7xl mx-auto">
        <PageHeader title="Le Nostre Categorie" />

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 justify-items-center">
          {categories.map((category) => {
            const IconComponent = category.icon;
            return (
              <div
                key={category.id}
                className="group cursor-pointer transition-transform hover:scale-105"
              >
                <div
                  className={`
                    relative w-32 h-32 lg:w-36 lg:h-36 rounded-full ${category.color} 
                    flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow
                  `}
                >
                  {/* Icon in center */}
                  <div className="absolute inset-0 flex items-center justify-center">
                    <Image
                      src={category.icon}
                      alt={category.name}
                      width={48}
                      height={48}
                      className="h-12 w-12 lg:h-14 lg:w-14"
                    />
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}

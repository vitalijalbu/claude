import Link from "next/link";

const brandsByLetter = {
  A: [
    "Adele Fragranze",
    "Alpe Pragas",
    "Amedei",
    "Andree jardin",
    "Anonima barbieri",
    "Asa selection",
    "Ambiente",
    "Amedei",
    "Andreola",
    "Apoem",
    "Auotore",
    "Alpe magna",
    "Ambroso",
    "Amefa",
    "Anna paghera",
    "Acquanova",
  ],
  B: [
    "Baobab",
    "Bere e Passione",
    "Birrificio manerba",
    "Blanc Mariclo",
    "Bondi Wash",
    "Brecourt",
    "Berghoff",
    "Bitossi home",
    "Blim",
    "Bonverre",
    "Bullfrog",
    "Bastion collections",
    "Biella Fabrics",
    "Bjork e berries",
    "Bon Parfumer",
    "Botanicae",
  ],
  C: [
    "Captain Fawcett",
    "Casafina",
    "Cereria Molla",
    "Claus Porto",
    "Coola",
    "Cortenera",
    "Carlino",
    "Cavalieri",
    "Childhome",
    "Clean",
    "Coreterno",
    "Costa Nova",
    "Casa del dolce certolini",
    "Cawo",
    "Chrisopher vine",
    "Constesse",
    "Corrado Benedetti",
  ],
  D: [
    "easy Life",
    "Ellethic",
    "Enzo de Gasperi",
    "Essenza Home",
    "Elements lighting",
    "Emile henry",
    "Escentric Molecules",
    "Experimental perfume club",
    "Eleven People",
    "Emily evans",
    "Essential parfums",
    "Extrò Cosmesi",
  ],
};

type BrandSectionProps = {
  letter: string;
  brands: string[];
};

const BrandSection = ({ letter, brands }: BrandSectionProps) => (
  <div className="space-y-6">
    <h2 className="text-xl font-medium text-gray-900">{letter}</h2>
    <div className="grid grid-cols-3 gap-6">
      {Array.from({ length: 3 }, (_, i) => {
        const itemsPerColumn = Math.ceil(brands.length / 3);
        const start = i * itemsPerColumn;
        const columnBrands = brands.slice(start, start + itemsPerColumn);

        return (
          <div key={i} className="space-y-2">
            {columnBrands.map((brand, j) => (
              <div key={j} className="text-sm text-gray-700 leading-relaxed">
                <Link href={`/brand/${brand}`}>{brand}</Link>
              </div>
            ))}
          </div>
        );
      })}
    </div>
  </div>
);

export default function Page() {
  return (
    <section className="container py-10">
      <h1 className="text-4xl font-light text-center mb-16 text-gray-900">
        I nostri brand
      </h1>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-16">
        <div className="space-y-12">
          <BrandSection letter="A" brands={brandsByLetter.A} />
          <BrandSection letter="C" brands={brandsByLetter.C} />
        </div>

        <div className="space-y-12">
          <BrandSection letter="B" brands={brandsByLetter.B} />
          <BrandSection letter="D" brands={brandsByLetter.D} />
        </div>
      </div>
    </section>
  );
}

import Link from "next/link";

import { Button, Input } from "@heroui/react";
import Image from "next/image";
import { ArrowRight, Mail, MapPinned, Phone } from "lucide-react";

export function SiteFooter() {
  return (
    <footer className="bg-black text-white">
      <div className="container py-12">
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
          <div>
            <Link
              href="/"
              className="mb-4 inline-block font-serif text-2xl font-light"
            >
              <Image
                width={150}
                height={50}
                alt="STILE STORE"
                src="/images/logo-light.svg"
              />
            </Link>
            <p className="mb-4 text-sm text-gray-400">
              SJILE COSMETICA è un negozio di cosmetica naturale e biologica
              specializzato in prodotti per la cura della pelle e dei capelli.
            </p>
            <p className="text-sm text-gray-400">© 2025 SJILE COSMETICA</p>
          </div>
          <div>
            <h3 className="mb-4 text-lg font-medium">I nostri prodotti</h3>
            <ul className="space-y-2 text-sm text-gray-400">
              <li>
                <Link href="#" className="hover:text-white">
                  Bestseller
                </Link>
              </li>
              <li>
                <Link href="#" className="hover:text-white">
                  Novità
                </Link>
              </li>
              <li>
                <Link href="#" className="hover:text-white">
                  Offerte
                </Link>
              </li>
              <li>
                <Link href="/brand" className="hover:text-white">
                  Brand
                </Link>
              </li>
              <li>
                <Link href="#" className="hover:text-white">
                  Fragranze e Beauty
                </Link>
              </li>
            </ul>
          </div>
          <div>
            <h3 className="mb-4 text-lg font-medium">Contatti</h3>
            <ul className="space-y-2 text-sm text-gray-400">
              <li className="flex items-start gap-2">
                <Mail size={18} />
                <span>info@stile.cosmetica.it</span>
              </li>
              <li className="flex items-start gap-2">
                <MapPinned />
                <span>
                  Via Garibaldi 34, Montecchio Maggiore, Vicenza, Italia
                </span>
              </li>
              <li className="flex items-start gap-2">
                <Phone size={18}/>
                <span>0444.555.123</span>
              </li>
            </ul>
          </div>
          <div>
            <h3 className="mb-4 text-lg font-medium">
              Iscriviti alla newsletter
            </h3>
            <p className="mb-4 text-sm text-gray-400">
              Ricevi aggiornamenti sui nuovi prodotti e offerte speciali.
            </p>
            <div className="flex gap-2">
              <Input
                type="email"
                placeholder="La tua email"
                className="border-gray-700 bg-gray-900 text-white placeholder:text-gray-500 focus:border-gray-600 focus:ring-0"
              />
              <Button
                type="submit"
                className="shrink-0 bg-white text-black hover:bg-gray-200"
                isIconOnly
              >
                <ArrowRight size={16} className="text-black" />
              </Button>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}

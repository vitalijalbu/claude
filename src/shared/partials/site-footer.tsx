import Link from "next/link"

import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"

export function SiteFooter() {
  return (
    <footer className="bg-black text-white">
      <div className="container py-12">
        <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
          <div>
            <Link href="/" className="mb-4 inline-block font-serif text-2xl font-light">
              stile
            </Link>
            <p className="mb-4 text-sm text-gray-400">
              SJILE COSMETICA è un negozio di cosmetica naturale e biologica specializzato in prodotti per la cura della
              pelle e dei capelli.
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
                <Link href="#" className="hover:text-white">
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
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="mt-0.5 h-4 w-4 shrink-0"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
                <span>info@stile.cosmetica.it</span>
              </li>
              <li className="flex items-start gap-2">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="mt-0.5 h-4 w-4 shrink-0"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                  />
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
                <span>Via Garibaldi 34, Montecchio Maggiore, Vicenza, Italia</span>
              </li>
              <li className="flex items-start gap-2">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="mt-0.5 h-4 w-4 shrink-0"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                  />
                </svg>
                <span>0444.555.123</span>
              </li>
            </ul>
          </div>
          <div>
            <h3 className="mb-4 text-lg font-medium">Iscriviti alla newsletter</h3>
            <p className="mb-4 text-sm text-gray-400">Ricevi aggiornamenti sui nuovi prodotti e offerte speciali.</p>
            <div className="flex gap-2">
              <Input
                type="email"
                placeholder="La tua email"
                className="border-gray-700 bg-gray-900 text-white placeholder:text-gray-500 focus:border-gray-600 focus:ring-0"
              />
              <Button type="submit" className="shrink-0 bg-white text-black hover:bg-gray-200">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="h-4 w-4"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </Button>
            </div>
          </div>
        </div>
      </div>
    </footer>
  )
}

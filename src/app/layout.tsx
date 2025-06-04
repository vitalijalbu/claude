"use client";
import { Playfair_Display } from "next/font/google";
import "@/assets/styles/app.css";
import { QueryClientProvider } from "@tanstack/react-query";
import { ReactQueryDevtools } from "@tanstack/react-query-devtools";
import queryClient from "@/lib/react-query-client";
import { SiteHeader } from "@/shared/partials/site-header";
import { SiteFooter } from "@/shared/partials/site-footer";

import { ReactNode } from "react";

const Playfair = Playfair_Display({
  subsets: ["latin"],
  weight: ["400", "500", "600", "700"],
  variable: "--font-playfair",
});

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="it" className={Playfair.variable}>
      <body>
        <QueryClientProvider client={queryClient}>
          <ReactQueryDevtools initialIsOpen={false} position="right" />
          <SiteHeader />
          {children}
          <SiteFooter />
        </QueryClientProvider>
      </body>
    </html>
  );
}

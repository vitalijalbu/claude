"use client";
import Link from "next/link";
import { ProductCard } from "@/shared/snippets/product-card";
import { useList } from "@/hooks";
import { Card, CardBody } from "@heroui/react";
import Image from "next/image";

export default function Page() {
  const userData = {
    name: "Nome e cognome",
    cardNumber: "01234567899",
    points: "2346 pt",
  };

  return (
    <div className="flex min-h-screen flex-col">
      <Card className="w-full border" shadow="none">
        <CardBody className="p-8">
          {/* Header Info */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div>
              <h3 className="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">
                Nome e Cognome
              </h3>
              <p className="text-gray-800">{userData.name}</p>
            </div>

            <div>
              <h3 className="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">
                Numero Fidelity Card
              </h3>
              <p className="text-gray-800">{userData.cardNumber}</p>
            </div>

            <div>
              <h3 className="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">
                Punti Accumulati
              </h3>
              <p className="text-gray-800">{userData.points}</p>
            </div>
          </div>

          {/* Fidelity Card Visual */}
          <div className="w-full max-w-md">
            <div className="bg-[#e16a2e] rounded-lg overflow-hidden shadow-lg">
              <div className="grid grid-cols-[1fr_1px_1fr] h-48">
                {/* Left side - Logo */}
                <div className="flex items-center justify-center p-8">
                  <div className="text-white">
                    <Image
                      width={200}
                      height={200}
                      className="h-10 w-auto"
                      alt="Logo"
                      src="/images/logo-light.svg"
                    />
                  </div>
                </div>

                {/* Divider */}
                <div className="bg-white bg-opacity-20"></div>

                {/* Right side - Card Info */}
                <div className="flex flex-col justify-center p-6 text-white">
                  <div className="text-xs uppercase tracking-wider opacity-90 mb-1">
                    Fidelity Card
                  </div>
                  <div className="text-sm font-medium mb-3">
                    {userData.name}
                  </div>
                  <div className="text-xs opacity-90">
                    {userData.cardNumber}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </CardBody>
      </Card>
    </div>
  );
}

import React from "react";
import { Card, CardBody, CardHeader, Divider } from "@heroui/react";

export const OrderSummary = ({
  subtotal,
  iva,
  shipping,
  insurance,
  total,
}: any) => {
  return (
    <Card className="border" shadow="none">
      <CardHeader className="pb-3">
        <h2 className="text-xl font-semibold text-gray-800">
          Riepilogo Ordine
        </h2>
      </CardHeader>
      <CardBody className="space-y-3">
        <div className="flex justify-between">
          <span className="text-gray-600">SUBTOTALE</span>
          <span className="font-medium">{subtotal.toFixed(2)} €</span>
        </div>
        <div className="flex justify-between">
          <span className="text-gray-600">IVA</span>
          <span className="font-medium">{iva.toFixed(2)} €</span>
        </div>
        <div className="flex justify-between">
          <span className="text-gray-600">SPEDIZIONE</span>
          <span className="font-medium">{shipping.toFixed(2)} €</span>
        </div>
        {insurance > 0 && (
          <div className="flex justify-between">
            <span className="text-gray-600">ASSICURAZIONE</span>
            <span className="font-medium">{insurance.toFixed(2)} €</span>
          </div>
        )}

        <Divider />

        <div className="flex justify-between text-xl font-bold">
          <span>Totale:</span>
          <span>{total.toFixed(2)} €</span>
        </div>
      </CardBody>
    </Card>
  );
};

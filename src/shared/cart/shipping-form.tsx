import React from "react";
import {
  Card,
  CardBody,
  CardHeader,
  DatePicker,
  Input,
  Textarea,
} from "@heroui/react";

export const ShippingForm = ({ shippingDetails, onInputChange }: any) => {
  return (
    <Card className="border" shadow="none">
      <CardHeader className="pb-3">
        <h2 className="text-xl font-semibold text-gray-800">
          Indirizzo di spedizione
        </h2>
      </CardHeader>
      <CardBody className="space-y-4">
        <Input
          label="NOME E COGNOME"
          placeholder="Nome e Cognome"
          value={shippingDetails.firstName}
          onValueChange={(value) => onInputChange("firstName", value)}
          variant="underlined"
        />

        <DatePicker
          label="DATA DI NASCITA"
          //value={shippingDetails.birthDate}
          variant="underlined"
        />

        <Input
          type="tel"
          label="NUMERO DI TELEFONO"
          placeholder="Inserisci numero di telefono"
          value={shippingDetails.phone}
          onValueChange={(value) => onInputChange("phone", value)}
          variant="underlined"
        />

        <Input
          type="email"
          label="EMAIL"
          placeholder="Inserisci email"
          value={shippingDetails.email}
          onValueChange={(value) => onInputChange("email", value)}
          variant="underlined"
        />

        <Input
          label="VIA"
          placeholder="Inserisci via"
          value={shippingDetails.address}
          onValueChange={(value) => onInputChange("address", value)}
          variant="underlined"
        />

        <div className="grid grid-cols-2 gap-4">
          <Input
            label="CITTÀ"
            placeholder="Inserisci città"
            value={shippingDetails.city}
            onValueChange={(value) => onInputChange("city", value)}
            variant="underlined"
          />
          <Input
            label="CAP"
            placeholder="Inserisci cap"
            value={shippingDetails.cap}
            onValueChange={(value) => onInputChange("cap", value)}
            variant="underlined"
          />
        </div>

        <Textarea
          label="NOTE AGGIUNTIVE"
          placeholder="Inserisci note aggiuntive"
          value={shippingDetails.notes}
          onValueChange={(value) => onInputChange("notes", value)}
          variant="underlined"
          minRows={3}
        />
      </CardBody>
    </Card>
  );
};

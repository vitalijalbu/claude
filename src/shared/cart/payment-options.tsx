import React from "react";
import {
  Card,
  CardBody,
  CardHeader,
  Button,
  Input,
  Radio,
  RadioGroup,
  Divider,
  Checkbox,
} from "@heroui/react";

export const PaymentOptions = ({
  subtotal,
  wantInsurance,
  onInsuranceToggle,
  promoCode,
  onPromoCodeChange,
  billingType,
  onBillingTypeChange,
  paymentMethod,
  onPaymentMethodChange,
}: any) => {
  const paymentMethods = [
    { id: "nexi", label: "Nexi", className: "bg-blue-600 text-white" },
    { id: "paypal", label: "PayPal", className: "bg-yellow-400 text-gray-900" },
    { id: "apple", label: "Apple Pay", className: "bg-black text-white" },
    {
      id: "google",
      label: "Google Pay",
      className: "border-2 border-gray-300",
    },
  ];

  return (
    <Card className="border" shadow="none">
      <CardHeader className="pb-3">
        <h2 className="text-xl font-semibold text-gray-800">
          Opzioni di Pagamento
        </h2>
      </CardHeader>
      <CardBody className="space-y-6">
        {/* Insurance Option */}
        <div className="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
          <Checkbox onChange={(e) => onInsuranceToggle(e.target.checked)}>
            Voglio assicurare i miei prodotti
          </Checkbox>
          <span className="text-sm font-medium">+ 2,00 €</span>
        </div>

        {/* Free Shipping Notification */}
        {subtotal < 120 && (
          <div className="p-3 bg-orange-50 rounded-lg">
            <p className="text-sm text-orange-700">
              Ti mancano {(120 - subtotal).toFixed(2)} euro per raggiungere la
              spedizione gratuita!
            </p>
          </div>
        )}

        {/* Promo Code */}
        <Input
          placeholder="INSERISCI CODICE SCONTO"
          value={promoCode}
          onValueChange={onPromoCodeChange}
          variant="bordered"
          endContent={
            <Button color="primary" variant="flat">
              Applica
            </Button>
          }
        />

        <Divider />

        {/* Billing Options */}
        <div className="space-y-4">
          <h3 className="font-semibold">Desideri fattura?</h3>
          <RadioGroup
            orientation="horizontal"
            value={billingType}
            onValueChange={onBillingTypeChange}
            className="flex gap-6"
          >
            <Radio value="azienda">AZIENDA</Radio>
            <Radio value="privato">PRIVATO</Radio>
          </RadioGroup>

          <div className="flex items-start gap-3">
            <Checkbox value="same-billing" defaultChecked>
              L'indirizzo di fatturazione è uguale all'indirizzo di spedizione
            </Checkbox>
          </div>
        </div>

        <Divider />

        {/* Payment Methods */}
        <div className="space-y-4">
          <h3 className="font-semibold">Scegli il metodo di pagamento</h3>
          <div className="space-y-3">
            {paymentMethods.map((method) => (
              <Button
                key={method.id}
                className={`w-full h-12 font-semibold ${method.className}`}
                size="lg"
                variant={method.id === "google" ? "bordered" : "solid"}
                onPress={() => onPaymentMethodChange(method.id)}
              >
                {method.label}
              </Button>
            ))}
          </div>
        </div>
      </CardBody>
    </Card>
  );
};

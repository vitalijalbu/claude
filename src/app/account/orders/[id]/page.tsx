import React from "react";
import {
  Card,
  CardBody,
  CardHeader,
  Button,
  Image,
  Chip,
  Divider,
  Link,
} from "@heroui/react";
import {
  Package,
  Truck,
  CheckCircle,
  Download,
  RotateCcw,
  MapPin,
  CreditCard,
  Phone,
  Mail,
  Clock,
  ChevronLeft,
} from "lucide-react";
import { PageHeader } from "@/shared/components";

export default function Page() {
  const orderData = {
    id: "#ORD-2024-001234",
    status: "shipped",
    date: "15 maggio 2024",
    estimatedDelivery: "18-20 maggio 2024",
    total: "€189.97",
    items: [
      {
        id: 1,
        name: "Cuffie Bluetooth Wireless",
        variant: "Nero, Taglia M",
        image:
          "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop",
        price: "€79.99",
        quantity: 1,
        total: "€79.99",
      },
      {
        id: 2,
        name: "Cavo USB-C 2m",
        variant: "Bianco",
        image:
          "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=300&fit=crop",
        price: "€19.99",
        quantity: 2,
        total: "€39.98",
      },
      {
        id: 3,
        name: "Custodia Premium",
        variant: "Trasparente, iPhone 15 Pro",
        image:
          "https://images.unsplash.com/photo-1601593346740-925612772716?w=300&h=300&fit=crop",
        price: "€34.99",
        quantity: 2,
        total: "€69.98",
      },
    ],
    tracking: [
      {
        status: "Ordine ricevuto",
        date: "15 Mag, 10:30",
        completed: true,
        description: "Il tuo ordine è stato ricevuto",
      },
      {
        status: "Pagamento confermato",
        date: "15 Mag, 10:32",
        completed: true,
        description: "Pagamento elaborato con successo",
      },
      {
        status: "In preparazione",
        date: "15 Mag, 14:15",
        completed: true,
        description: "Articoli in preparazione per la spedizione",
      },
      {
        status: "Spedito",
        date: "16 Mag, 09:45",
        completed: true,
        description: "Pacco spedito tramite Corriere Espresso",
        trackingNumber: "ED123456789IT",
      },
      {
        status: "In consegna",
        date: "Previsto: 18 Mag",
        completed: false,
        description: "Il pacco sarà consegnato oggi",
      },
      {
        status: "Consegnato",
        date: "Previsto: 18 Mag",
        completed: false,
        description: "Pacco consegnato con successo",
      },
    ],
    addresses: {
      shipping: {
        name: "Mario Rossi",
        street: "Via Roma, 123",
        city: "Milano, 20121",
        country: "Italia",
        phone: "+39 123 456 7890",
      },
      billing: {
        name: "Mario Rossi",
        street: "Via Roma, 123",
        city: "Milano, 20121",
        country: "Italia",
        email: "mario.rossi@email.com",
      },
    },
    payment: { method: "Carta di Credito", last4: "4242", brand: "Visa" },
  };

  const statusConfig = {
    pending: { color: "secondary", icon: Package },
    processing: { color: "primary", icon: Package },
    shipped: { color: "primary", icon: Truck },
    delivered: { color: "success", icon: CheckCircle },
    cancelled: { color: "danger", icon: Package },
  };

  const StatusIcon = statusConfig[orderData.status]?.icon || Package;

  return (
    <div className="max-w-6xl mx-auto p-4 md:p-6 space-y-6">
      {/* Header */}
      <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <Button as={Link} href="/account/orders" variant="light">
            <ChevronLeft size={20} />
            Indietro
          </Button>
          <h1 className="text-2xl">Dettagli ordine</h1>
          <p className="text-gray-600">
            Numero {orderData.id} • Data {orderData.date}
          </p>
        </div>
        <div className="flex flex-wrap gap-3">
          <Button
            variant="bordered"
            startContent={<Download className="w-4 h-4" />}
          >
            Scarica ricevuta
          </Button>
          <Button
            color="primary"
            startContent={<RotateCcw className="w-4 h-4" />}
          >
            Riacquista
          </Button>
        </div>
      </div>

      {/* Status Banner */}
      <Card className="border" shadow="none">
        <CardBody className="p-4 md:p-6">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div className="flex items-center gap-4">
              <div className="p-3 bg-blue-100 rounded-full">
                <StatusIcon className="w-4 h-4" />
              </div>
              <div>
                <Chip
                  color={statusConfig[orderData.status]?.color}
                  className="capitalize font-semibold mb-2"
                >
                  {orderData.status === "shipped"
                    ? "Spedito"
                    : orderData.status}
                </Chip>
                <p className="text-sm text-gray-600">
                  Consegna prevista:{" "}
                  <strong>{orderData.estimatedDelivery}</strong>
                </p>
              </div>
            </div>
            <div className="text-center sm:text-right">
              <p className="text-2xl font-bold text-gray-900">
                {orderData.total}
              </p>
              <p className="text-sm text-gray-600">Totale</p>
            </div>
          </div>
        </CardBody>
      </Card>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 space-y-6">
          {/* Order Items */}
          <Card className="border" shadow="none">
            <CardHeader className="flex-row items-center justify-between">
              <h2 className="text-xl font-semibold">Prodotti</h2>
              <Chip variant="flat" color="primary">
                {orderData.items.length} articoli
              </Chip>
            </CardHeader>
            <CardBody className="space-y-4">
              {orderData.items.map((item, index) => (
                <div key={item.id}>
                  <div className="flex gap-4">
                    <Image
                      src={item.image}
                      alt={item.name}
                      width={60}
                      height={60}
                      className="object-cover rounded-lg flex-shrink-0 md:w-20 md:h-20"
                    />
                    <div className="flex-1 min-w-0">
                      <h3 className="font-semibold text-gray-900 truncate text-sm md:text-base">
                        {item.name}
                      </h3>
                      <p className="text-xs md:text-sm text-gray-600 mt-1">
                        {item.variant}
                      </p>
                      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-2 gap-2">
                        <div className="flex items-center gap-4 text-xs md:text-sm">
                          <span className="text-gray-600">
                            Qtà: {item.quantity}
                          </span>
                          <span className="text-gray-600">
                            Prezzo: {item.price}
                          </span>
                        </div>
                        <span className="font-semibold text-gray-900">
                          {item.total}
                        </span>
                      </div>
                    </div>
                  </div>
                  {index < orderData.items.length - 1 && (
                    <Divider className="mt-4" />
                  )}
                </div>
              ))}

              <Divider className="my-4" />

              {/* Summary */}
              <div className="space-y-2">
                {[
                  { label: "Subtotale", value: "€189.95" },
                  { label: "Spedizione", value: "Gratuita" },
                  { label: "Tasse", value: "€0.02" },
                ].map(({ label, value }) => (
                  <div key={label} className="flex justify-between text-sm">
                    <span className="text-gray-600">{label}</span>
                    <span>{value}</span>
                  </div>
                ))}
                <Divider />
                <div className="flex justify-between font-semibold text-lg">
                  <span>Totale</span>
                  <span>{orderData.total}</span>
                </div>
              </div>
            </CardBody>
          </Card>

          {/* Tracking */}
          <Card className="border" shadow="none">
            <CardHeader>
              <h2 className="text-xl font-semibold">Stato spedizione</h2>
            </CardHeader>
            <CardBody className="space-y-4">
              {orderData.tracking.map((step, index) => (
                <div key={index} className="flex gap-3">
                  <div className="flex flex-col items-center">
                    <div
                      className={`w-8 h-8 rounded-full flex items-center justify-center ${step.completed ? "bg-green-100 text-green-600" : "bg-gray-100 text-gray-400"}`}
                    >
                      {step.completed ? (
                        <CheckCircle className="w-4 h-4" />
                      ) : (
                        <Clock className="w-4 h-4" />
                      )}
                    </div>
                    {index < orderData.tracking.length - 1 && (
                      <div
                        className={`w-0.5 h-8 mt-1 ${step.completed ? "bg-green-200" : "bg-gray-200"}`}
                      />
                    )}
                  </div>
                  <div className="flex-1">
                    <div className="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3">
                      <h3
                        className={`font-semibold text-sm ${step.completed ? "text-gray-900" : "text-gray-500"}`}
                      >
                        {step.status}
                      </h3>
                      <span className="text-xs text-gray-500">{step.date}</span>
                    </div>
                    <p className="text-xs text-gray-600 mt-1">
                      {step.description}
                    </p>
                    {step.trackingNumber && (
                      <Link href="#" className="text-xs text-primary mt-1">
                        Codice: {step.trackingNumber}
                      </Link>
                    )}
                  </div>
                </div>
              ))}
            </CardBody>
          </Card>
        </div>

        <div className="space-y-4">
          {/* Addresses */}
          {[
            {
              title: "Indirizzo spedizione",
              icon: MapPin,
              data: orderData.addresses.shipping,
              contact: "phone",
            },
            {
              title: "Indirizzo fatturazione",
              icon: MapPin,
              data: orderData.addresses.billing,
              contact: "email",
            },
          ].map(({ title, icon: Icon, data, contact }) => (
            <Card key={title} className="border" shadow="none">
              <CardHeader className="pb-3">
                <div className="flex items-center gap-2">
                  <Icon className="w-4 h-4 text-gray-600" />
                  <h3 className="font-semibold">{title}</h3>
                </div>
              </CardHeader>
              <CardBody className="pt-0 space-y-1 text-sm">
                <p className="font-semibold text-gray-900">{data.name}</p>
                <p className="text-gray-600">{data.street}</p>
                <p className="text-gray-600">{data.city}</p>
                <p className="text-gray-600">{data.country}</p>
                <div className="flex items-center gap-2 mt-2 pt-2 border-t">
                  {contact === "phone" ? (
                    <Phone className="w-3 h-3 text-gray-500" />
                  ) : (
                    <Mail className="w-3 h-3 text-gray-500" />
                  )}
                  <span className="text-gray-600 text-xs">{data[contact]}</span>
                </div>
              </CardBody>
            </Card>
          ))}

          {/* Payment */}
          <Card className="border" shadow="none">
            <CardHeader className="pb-3">
              <div className="flex items-center gap-2">
                <CreditCard className="w-4 h-4 text-gray-600" />
                <h3 className="font-semibold">Metodo di pagamento</h3>
              </div>
            </CardHeader>
            <CardBody className="pt-0">
              <div className="flex items-center gap-3">
                <div className="w-10 h-6 bg-gradient-to-r from-blue-600 to-blue-400 rounded flex items-center justify-center">
                  <span className="text-white text-xs font-bold">VISA</span>
                </div>
                <div>
                  <p className="font-semibold text-gray-900 text-sm">
                    •••• {orderData.payment.last4}
                  </p>
                  <p className="text-xs text-gray-600">
                    {orderData.payment.method}
                  </p>
                </div>
              </div>
            </CardBody>
          </Card>

          {/* Support */}
          <Card className="border" shadow="none">
            <CardBody className="text-center p-4">
              <h3 className="font-semibold text-gray-900 mb-2">
                Hai bisogno di aiuto?
              </h3>
              <p className="text-sm text-gray-600 mb-3">
                Per qualsiasi domanda sul tuo ordine, contatta il nostro
                servizio clienti.
              </p>
              <Button
                color="primary"
                variant="flat"
                size="sm"
                as="a"
                href="mailto:info@stile.cosmetica.it"
              >
                Contatta il supporto
              </Button>
            </CardBody>
          </Card>
        </div>
      </div>
    </div>
  );
}

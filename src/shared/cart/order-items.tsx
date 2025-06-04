import React from "react";
import { Card, CardBody, Button, Image, Chip } from "@heroui/react";
import { Trash2, Plus, Minus } from "lucide-react";

export const OrderItems = ({ items, onUpdateQuantity, onRemove }: any) => {
  return (
    <div className="space-y-6">
      {items.map((item: any) => (
        <Card key={item.id} className="border" shadow="none">
          <CardBody className="p-6">
            <div className="flex gap-4">
              <div className="flex-shrink-0">
                <Image
                  src={item.image}
                  alt={item.name}
                  width={96}
                  height={96}
                  className="rounded-lg object-cover"
                />
              </div>

              <div className="flex-grow">
                <div className="flex justify-between items-start mb-3">
                  <div>
                    <Chip variant="flat" color="default" className="mb-2">
                      {item.category}
                    </Chip>
                    <h3 className="font-semibold text-lg text-gray-800">
                      {item.name}
                    </h3>
                    <p className="text-gray-600 text-sm">{item.description}</p>
                    {item.color && (
                      <p className="text-gray-500 text-sm mt-1">
                        Colore: {item.color}
                      </p>
                    )}
                  </div>
                  <Button
                    isIconOnly
                    variant="light"
                    color="danger"
                    onPress={() => onRemove(item.id)}
                    className="text-gray-400 hover:text-red-500"
                  >
                    <Trash2 size={18} />
                  </Button>
                </div>

                <div className="flex items-center justify-between">
                  <div className="flex items-center gap-2">
                    <Button
                      isIconOnly
                      variant="bordered"
                      onPress={() =>
                        onUpdateQuantity(item.id, item.quantity - 1)
                      }
                      isDisabled={item.quantity <= 1}
                    >
                      <Minus size={14} />
                    </Button>
                    <span className="w-12 text-center font-medium text-lg">
                      {item.quantity}
                    </span>
                    <Button
                      isIconOnly
                      variant="bordered"
                      onPress={() =>
                        onUpdateQuantity(item.id, item.quantity + 1)
                      }
                    >
                      <Plus size={14} />
                    </Button>
                  </div>
                  <p className="font-bold text-xl">
                    {(item.price * item.quantity).toFixed(2)}€
                  </p>
                </div>
              </div>
            </div>
          </CardBody>
        </Card>
      ))}
    </div>
  );
};

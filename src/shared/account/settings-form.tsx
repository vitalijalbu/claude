import React from "react";
import { Button, Card, CardBody, Input, Avatar } from "@heroui/react";
import { Edit, Plus } from "lucide-react";

interface AddressData {
  id: number;
  name: string;
  phone: string;
  email: string;
  street?: string;
  city?: string;
  cap?: string;
}

const EditableField = ({
  label,
  value,
  isEditing,
  onChange,
}: {
  label: string;
  value: string;
  isEditing: boolean;
  onChange: (value: string) => void;
}) => (
  <div>
    <p className="text-xs text-default-500 uppercase font-medium mb-1">
      {label}
    </p>
    {isEditing ? (
      <Input value={value} onValueChange={onChange} variant="bordered" />
    ) : (
      <p className="text-sm">{value}</p>
    )}
  </div>
);

const EditableSection = ({
  title,
  isEditing,
  onEdit,
  onSave,
  onCancel,
  children,
}: {
  title: string;
  isEditing: boolean;
  onEdit: () => void;
  onSave: () => void;
  onCancel: () => void;
  children: React.ReactNode;
}) => (
  <Card className="mb-6 border" shadow="none">
    <CardBody className="p-6">
      <div className="flex justify-between items-center mb-4">
        <h2 className="text-xl font-semibold">{title}</h2>
        {!isEditing ? (
          <Button isIconOnly variant="light" onPress={onEdit}>
            <Edit className="w-5 h-5" />
          </Button>
        ) : (
          <div className="flex gap-2">
            <Button variant="light" color="danger" onPress={onCancel}>
              Annulla
            </Button>
            <Button color="primary" onPress={onSave}>
              Salva
            </Button>
          </div>
        )}
      </div>
      {children}
    </CardBody>
  </Card>
);

export default function SettingsPage() {
  const [editingSection, setEditingSection] = React.useState<string | null>(
    null,
  );
  const [personalInfo, setPersonalInfo] = React.useState({
    name: "Nome e cognome",
    phone: "+39 1234567890",
    birthDate: "21/03/01",
    email: "nomecognome@mail.com",
  });

  const [addresses, setAddresses] = React.useState<AddressData[]>([
    {
      id: 1,
      name: "Nome e cognome",
      phone: "+39 1234567890",
      email: "nomecognome@mail.com",
      street: "Via",
      city: "Città",
      cap: "00000",
    },
    {
      id: 2,
      name: "Nome e cognome",
      phone: "+39 1234567890",
      email: "nomecognome@mail.com",
      street: "Via",
      city: "Città",
      cap: "00000",
    },
  ]);

  const [tempData, setTempData] = React.useState<any>({});

  const handleEdit = (section: string, data: any) => {
    setEditingSection(section);
    setTempData({ ...data });
  };

  const handleSave = (section: string) => {
    if (section === "personal") {
      setPersonalInfo(tempData);
    } else {
      const addressId = parseInt(section.split("-")[1]);
      setAddresses((prev) =>
        prev.map((addr) => (addr.id === addressId ? tempData : addr)),
      );
    }
    setEditingSection(null);
  };

  const handleCancel = () => {
    setEditingSection(null);
    setTempData({});
  };

  const updateTempData = (field: string, value: string) => {
    setTempData((prev: any) => ({ ...prev, [field]: value }));
  };

  const handleAddAddress = () => {
    const newAddress = {
      id: Math.max(...addresses.map((addr) => addr.id), 0) + 1,
      name: "",
      phone: "",
      email: "",
      street: "",
      city: "",
      cap: "",
    };
    setAddresses((prev) => [...prev, newAddress]);
    handleEdit(`address-${newAddress.id}`, newAddress);
  };

  const isEditing = (section: string) => editingSection === section;
  const getCurrentData = (section: string, fallback: any) =>
    isEditing(section) ? tempData : fallback;

  return (
    <div>
      {/* Personal Information */}
      <EditableSection
        title="Info personali"
        isEditing={isEditing("personal")}
        onEdit={() => handleEdit("personal", personalInfo)}
        onSave={() => handleSave("personal")}
        onCancel={handleCancel}
      >
        <Avatar
          src="https://img.heroui.chat/image/avatar?w=200&h=200&u=1"
          className="w-16 h-16 mb-6"
        />
        <div className="space-y-4">
          {[
            { label: "NOME E COGNOME", field: "name" },
            { label: "NUMERO DI TELEFONO", field: "phone" },
            { label: "DATA DI NASCITA", field: "birthDate" },
            { label: "EMAIL", field: "email" },
          ].map(({ label, field }) => (
            <EditableField
              key={field}
              label={label}
              value={getCurrentData("personal", personalInfo)[field] || ""}
              isEditing={isEditing("personal")}
              onChange={(value) => updateTempData(field, value)}
            />
          ))}
        </div>
      </EditableSection>

      {/* Shipping Addresses */}
      <h2 className="text-xl font-semibold mb-4">Indirizzo di spedizione</h2>

      {addresses.map((address) => (
        <EditableSection
          key={address.id}
          title={`Indirizzo ${address.id}`}
          isEditing={isEditing(`address-${address.id}`)}
          onEdit={() => handleEdit(`address-${address.id}`, address)}
          onSave={() => handleSave(`address-${address.id}`)}
          onCancel={handleCancel}
        >
          <div className="space-y-4">
            {[
              { label: "NOME E COGNOME", field: "name" },
              { label: "NUMERO DI TELEFONO", field: "phone" },
              { label: "EMAIL", field: "email" },
              { label: "VIA", field: "street" },
            ].map(({ label, field }) => (
              <EditableField
                key={field}
                label={label}
                value={
                  getCurrentData(`address-${address.id}`, address)[field] || ""
                }
                isEditing={isEditing(`address-${address.id}`)}
                onChange={(value) => updateTempData(field, value)}
              />
            ))}

            <div className="grid grid-cols-2 gap-4">
              {[
                { label: "CITTÀ", field: "city" },
                { label: "CAP", field: "cap" },
              ].map(({ label, field }) => (
                <EditableField
                  key={field}
                  label={label}
                  value={
                    getCurrentData(`address-${address.id}`, address)[field] ||
                    ""
                  }
                  isEditing={isEditing(`address-${address.id}`)}
                  onChange={(value) => updateTempData(field, value)}
                />
              ))}
            </div>
          </div>
        </EditableSection>
      ))}

      <Button
        className="w-full mt-2"
        variant="bordered"
        onPress={handleAddAddress}
        startContent={<Plus />}
      >
        Aggiungi indirizzo
      </Button>
    </div>
  );
}

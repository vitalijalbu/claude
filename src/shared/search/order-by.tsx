import React from "react";
import {
  Button,
  Dropdown,
  DropdownTrigger,
  DropdownMenu,
  DropdownItem,
} from "@heroui/react";
import { Filter, Settings2 } from "lucide-react";

export const OrderBy = (props: any) => {
  const [selectedKeys, setSelectedKeys] = React.useState(new Set(["text"]));

  const selectedValue = React.useMemo(
    () => Array.from(selectedKeys).join(", ").replace(/_/g, ""),
    [selectedKeys],
  );

  return (
    <Dropdown>
      <DropdownTrigger>
        <Button
          variant="light"
          className="uppsercase"
          endContent={<Settings2 size={20} />}
        >
          Filtri
        </Button>
      </DropdownTrigger>
      <DropdownMenu
        disallowEmptySelection
        aria-label="Single selection example"
        selectedKeys={selectedKeys}
        selectionMode="single"
        variant="flat"
        onSelectionChange={setSelectedKeys}
      >
        <DropdownItem key="price">Prezzo: dal più basso</DropdownItem>
        <DropdownItem key="-price">Prezzo: dal più alto</DropdownItem>
        <DropdownItem key="az">Dalla a alla Z</DropdownItem>
        <DropdownItem key="loved">I più amati</DropdownItem>
        <DropdownItem key="rating">Valutazione: dal più basso</DropdownItem>
        <DropdownItem key="availability">Disponibile</DropdownItem>
      </DropdownMenu>
    </Dropdown>
  );
};

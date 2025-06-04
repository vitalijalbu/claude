import React, { useMemo, useState } from "react";
import {
  Dropdown,
  DropdownTrigger,
  DropdownMenu,
  DropdownItem,
  Button,
} from "@heroui/react";
import { ChevronDown } from "lucide-react";

export default function SelectBrand() {
  const [selectedKeys, setSelectedKeys] = useState(new Set([]));

  const selectedValue = useMemo(
    () => Array.from(selectedKeys).join(", ").replaceAll("_", " "),
    [selectedKeys],
  );

  return (
    <Dropdown>
      <DropdownTrigger>
        <Button
          className="uppercase"
          endContent={<ChevronDown size={20} />}
          variant="bordered"
        >
          {selectedValue || "Brand"}
        </Button>
      </DropdownTrigger>
      <DropdownMenu
        disallowEmptySelection
        aria-label="Multiple selection example"
        closeOnSelect={false}
        selectedKeys={selectedKeys}
        selectionMode="multiple"
        variant="flat"
        onSelectionChange={setSelectedKeys}
      >
        <DropdownItem key="text">Text</DropdownItem>
        <DropdownItem key="number">Number</DropdownItem>
        <DropdownItem key="date">Date</DropdownItem>
        <DropdownItem key="single_date">Single Date</DropdownItem>
        <DropdownItem key="iteration">Iteration</DropdownItem>
      </DropdownMenu>
    </Dropdown>
  );
}

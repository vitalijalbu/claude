import React, { useState, useEffect } from "react";
import { Avatar, Button, Dropdown, Space } from "antd";
import PageActions from "@/shared/components/page-actions";
import {
  IconDots,
  IconPencilMinus,
  IconPlus,
  IconTrash,
} from "@tabler/icons-react";
import Datatable from "@/shared/datatable";
import DrawerRegion from "@/shared/geo/drawer-region";

const Regions = ({ props }) => {
  const [selected, setSelected] = useState(null);
  const [modal, setModal] = useState(false);
  const { page } = props;

  console.log("☘️ page:", page);

  const toggleModal = (record = null) => {
    setSelected(record);
    setModal(!modal);
  };

  const columns = [
    {
      title: "Name",
      key: "name",
      dataIndex: "name",
      sortable: true,
      filterSearch: true,
      fixed: true,
      
    },
    {
      title: "Paese",
      key: "country",
      sortable: true,
      filterSearch: true,
      fixed: true,
      render: ({ country }) => (
        <Space>
          <Avatar size="sm" src={`/images/flags/${country?.code}.svg`} />
          {country?.name}
        </Space>
      ),
    },
    {
      key: "actions",
      align: "right",
      render: (record) => (
        <Dropdown
          menu={{ items: tableActions }}
          placement="bottomRight"
          trigger={["click"]}
          onClick={() => setSelected(record)}
        >
          <Button type="text" icon={<IconDots />} />
        </Dropdown>
      ),
    },
  ];

  const tableActions = [
    {
      key: 1,
      onClick: () => toggleModal(selected),
      icon: <IconPencilMinus size={20} />,
      label: "Modifica",
    },
    {
      type: "divider",
    },
    {
      key: 3,
      danger: true,
      onClick: () => {
        alert("Elimina");
      },
      icon: <IconTrash size={20} />,
      label: "Elimina",
    },
  ];

  return (
    <>
      {modal && (
        <DrawerRegion
          isOpened={modal}
          initialData={selected}
          onClose={() => toggleModal()}
        />
      )}
      <div className="page">
        <PageActions
          backUrl="/geo"
          title={`Regioni (${page?.data?.total})`}
          extra={
            <Button
              type="primary"
              icon={<IconPlus />}
              onClick={() => toggleModal()}
            >
              Aggiungi
            </Button>
          }
        />
        <div className="page-content">
          <Datatable
            columns={columns}
            data={page?.data}
            endpoint="/suppliers"
            filters={page?.filters}
          />
        </div>
      </div>
    </>
  );
};

export default Regions;

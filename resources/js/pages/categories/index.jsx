import React, { useState, useEffect } from 'react';
import { Button, Dropdown } from 'antd';
import PageActions from '@/shared/components/page-actions';
import { IconDots, IconDownload, IconEdit, IconPencilMinus, IconPlus, IconTrash } from '@tabler/icons-react';
import Datatable from '@/shared/datatable';
import DrawerCategory from '@/shared/settings/drawer-category';

const Categories = ({props}) => {

    const [selected, setSelected] = useState(null);
    const [isLoading, setLoading] = useState(null);
    const [modal, setModal] = useState(false);
    const { page } = props;

    console.log('☘️ page:', page);

    const toggleModal = (record = null) => {
        setSelected(record);
        setModal(!modal);
    };

    const columns = [
        {
            title: 'Nome',
            key: 'title',
            dataIndex: 'title',
        },
        {
            title: 'Lingua',
            key: 'locale',
            dataIndex: 'locale',
            align: 'right',
        },
        {
            key: 'actions',
            align: 'right',
            render: (record) => (
                <Dropdown
                menu={{ items: tableActions }}
                placement='bottomRight'
                trigger={['click']}
                onClick={() => setSelected(record)}
              >
                <Button
                        type='text'
                        icon={<IconDots />}
                   />
              </Dropdown>

                   
            ),
        },
    ];


    const tableActions = [
        {
          key: 1,
          onClick: () => toggleModal(selected),
          icon: <IconPencilMinus size={20} />,
          label: 'Modifica',
        },
        {
          type: 'divider',
        },
        {
          key: 3,
          danger: true,
          onClick: () => {alert('Elimina')},
          icon: <IconTrash size={20} />,
          label: 'Elimina',
        },
      ];

    return (
        <>
            {modal && (
                <DrawerCategory
                    isOpened={modal}
                    initialData={selected}
                    onClose={() => toggleModal()}
                />
            )}
            <div className='page'>
                <PageActions
                    backUrl='/settings'
                    title={`Categorie (${page?.data?.total})`}
                    extra={
                        <Button
                            type='primary'
                            icon={<IconPlus />}
                            onClick={() => toggleModal()}
                        >
                            Aggiungi
                        </Button>
                    }
                />
                <div className='page-content'>
                    <Datatable
                        columns={columns}
                        data={page?.data}
                        endpoint='/suppliers'
                        filters={page?.filters}
                    />
                </div>
            </div>
        </>
    );
};

export default Categories;

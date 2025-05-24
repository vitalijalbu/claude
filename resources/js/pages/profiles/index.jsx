import React, { useState, useEffect } from 'react';
import { Avatar, Button, message, Space, Tag } from 'antd';
import PageActions from '@/shared/components/page-actions';
import { Link, useForm } from '@inertiajs/react';
import { IconDots, IconDownload, IconEdit, IconPlus } from '@tabler/icons-react';
import Datatable from '@/shared/datatable';
import ModalProfile from '@/shared/profiles/modal-supplier';
import { dateTimeFormatter } from '@/helpers/formatter';

const Index = ({props}) => {
    const { post, data, transform, setData } = useForm({
        provider: null,
    });
    const [selected, setSelected] = useState(null);
    const [isLoading, setLoading] = useState(null);
    const [modal, setModal] = useState(false);
    const { page } = props;

    const toggleModal = (record = null) => {
        setSelected(record);
        setModal(!modal);
    };



    const columns = [
        {
            title: 'Nome',
            key: 'name',
            sortable: true,
            filterSearch: true,
            fixed: true,
            render: (record) => (
                <Link href={`/profiles/${record?.id}`}>
                    <Space>
                       <Avatar src={record.media ? `/images/${record?.logo}` : '/images/placeholder.svg'} />
                        {record?.name}
                    </Space>
                </Link>
            ),
        },
        {
            title: 'Username',
            key: 'username',
            render: ({username}) => <Tag>{username}</Tag>,   
        },{
            title: 'Tot. annunci',
            key: 'listings',
            render: ({listings}) => listings?.length,   
        },
        {
            title: 'Creato il',
            key: 'created_at',
            type: 'datetime',
            align: 'right',
            sorter: (a, b) => a.created_at - b.created_at,
            render: (record) => (
                <span>{dateTimeFormatter(record?.created_at)}</span>
            ),
        },
        {
            key: 'actions',
            align: 'right',
            render: (record) => (
                    <Button
                        type='text'
                        icon={<IconDots />}
                        onClick={() => toggleModal(record)}
                   />
            ),
        },
    ];

    return (
        <>
            {modal && (
                <ModalProfile
                    isOpened={modal}
                    initialData={selected}
                    onClose={() => toggleModal()}
                />
            )}
            <div className='page'>
                <PageActions
                    title={`Profili (${page?.data?.total})`}
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

export default Index;

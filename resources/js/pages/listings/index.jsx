import React, { useState, useEffect } from 'react';
import { Avatar, Button, message, Space, Tag } from 'antd';
import PageActions from '@/shared/components/page-actions';
import { Link, useForm } from '@inertiajs/react';
import { IconDots, IconDownload, IconEdit, IconPlus } from '@tabler/icons-react';
import Datatable from '@/shared/datatable';
import { dateTimeFormatter } from '@/helpers/formatter';


const Index = ({props}) => {
    console.log('☘️ page:', props);
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
            title: 'Titolo',
            key: 'title',
            sortable: true,
            filterSearch: true,
            fixed: true,
            render: (record) => (
                <Link href={`/listings/${record?.id}`}>
                    <Space>
                        <Avatar shape="square" size={60} src={record.media ? `/images/${record?.logo}` : '/images/placeholder.svg'} />
                        {record?.title}
                    </Space>
                </Link>
            ),
        }, 
        {
            title: 'Profilo',
            key: 'profile',
            sortable: true,
            filterSearch: true,
            fixed: true,
            render: (record) => (
                <a href={`/profiles/${record.profile?.id}`} target='_blank'>
                    <Space>
                        <Avatar shape='square' src={record.media ? `/images/${record?.logo}` : '/images/placeholder.svg'} />
                        {record?.profile?.username}
                    </Space>
                </a>
            ),
        },
        {
            title: 'Categoria',
            key: 'category',
            render: ({category}) => (
                <Tag>{category?.title}</Tag>
            ),
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
            <div className='page'>
                <PageActions
                    title={`Annunci (${page?.data?.total})`}
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
    );
};

export default Index;

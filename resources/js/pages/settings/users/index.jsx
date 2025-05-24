import React, { useState, useEffect } from 'react';
import { Avatar, Button, message, Space, Tag } from 'antd';
import PageActions from '@/shared/components/page-actions';
import { Link, useForm } from '@inertiajs/react';
import { IconDots, IconDownload, IconEdit, IconPlus } from '@tabler/icons-react';
import Datatable from '@/shared/datatable';
import ModalProfile from '@/shared/profiles/modal-supplier';
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
            title: 'Nome',
            key: 'name',
            sortable: true,
            filterSearch: true,
            fixed: true,
            render: (record) => (
                <Link href={`/settings/users/${record?.id}`}>
                    <Space>
                       <Avatar src={record.media ? `/images/${record?.logo}` : '/images/placeholder.svg'} />
                        {record?.name}
                    </Space>
                </Link>
            ),
        },
        {
            title: 'Ruolo',
            key: 'roles',
            render: ({roles}) => roles && roles.length > 0 ? (
                <div>
                    {roles.map((role, index) => (
                        <Tag key={index}>{role}</Tag>
                    ))}
                </div>
            ) : '-',
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
        }
    ];

    return (
            <div className='page'>
                <PageActions
                    backUrl='/settings'
                    title={`Utenti (${page?.data?.total})`}
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

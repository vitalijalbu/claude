import React, { useState, useEffect } from 'react';
import { Avatar, Button, message, Space, Tag } from 'antd';
import PageActions from '@/shared/components/page-actions';
import { Link, useForm } from '@inertiajs/react';
import { IconDots, IconDownload, IconEdit, IconPlus } from '@tabler/icons-react';
import Datatable from '@/shared/datatable';
import { dateTimeFormatter } from '@/helpers/formatter';


const Media = ({props}) => {
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
            title: 'Name',
            key: 'file_name',
            sortable: true,
            filterSearch: true,
            fixed: true,
            render: (record) => (
                <Link href={`/media/${record?.id}`}>
                    <Space>
                        <Avatar shape="square" size={60} src={record.original_url ?? '/images/placeholder.svg'} />
                {record?.file_name}
                </Space>
                </Link>
            ),
        }, 
        {
            title: 'Collection',
            key: 'collection_name',
            render: ({collection_name}) => (
                <Tag>{collection_name}</Tag>
            ),
        },
        {
            title: 'Size',
            key: 'category',
            render: ({category}) => (
                <Tag>{category?.title}</Tag>
            ),
        }
    ];

    return (
            <div className='page'>
                <PageActions
                    title={`Media (${page?.data?.total})`}
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

export default Media;

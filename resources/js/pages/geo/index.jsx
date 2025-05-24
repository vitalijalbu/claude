import React, { useState, useEffect } from 'react';
import { Avatar, Button, Tag, Space } from 'antd';
import PageActions from '@/shared/components/page-actions';
import Datatable from '@/shared/datatable';
import { Link } from '@inertiajs/react';
import { IconEye } from '@tabler/icons-react';

const Countries = ({props}) => {

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
            title: 'Name',
            key: 'name',
            sortable: true,
            filterSearch: true,
            fixed: true,
            render: (record) => (
                <Link href={`/geo/${record?.id}`}>
                    <Space>
                        <Avatar size="sm" shape="square" src={`/images/flags/${record.code}.svg`} />
                {record?.name}
                </Space>
                </Link>
            ),
        }, 
        {
            title: 'Prefisso',
            key: 'phone_code',
            dataIndex: 'phone_code'
        }, 
        {
            title: 'Regioni',
            key: 'regions_count',
            dataIndex: 'regions_count'
        }, 
        {
            title: 'Città',
            key: 'cities',
            dataIndex: 'cities'
        },
        {
            align: 'right',
            render: (record) => (
                <Link href={`/geo/${record?.id}`}>
                    <Button icon={<IconEye/>} />
                </Link>
            ),
        }, 
    ];


    return (
            <div className='page'>
                <PageActions
                    title={`Paesi (${page?.data?.total})`}
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

export default Countries;

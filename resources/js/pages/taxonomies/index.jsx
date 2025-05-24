import React, { useState, useEffect } from 'react';
import { Avatar, Button, message, Space, Tag } from 'antd';
import PageActions from '@/shared/components/page-actions';
import { Link, useForm } from '@inertiajs/react';
import { IconPlus, IconTag } from '@tabler/icons-react';
import Datatable from '@/shared/datatable';


const Taxonomies = ({props}) => {
    console.log('☘️ page:', props);
    const { page } = props;

    const columns = [
        {
            title: 'Nome',
            key: 'name',
            sortable: true,
            filterSearch: true,
            fixed: true,
            render: (record) => (
                <Link href={`/taxonomies/${record?.id}`}>
                    <Space>
                       <Avatar icon={<IconTag color='#1677ff'/>} />
                        {record?.name}
                    </Space>
                </Link>
            ),
        },
        {
            title: 'Gruppo',
            key: 'group',
            render: ({group}) => (
                <Tag>
                    {group?.name}
                </Tag>
            ),
        },
        {
            title: 'Sito',
            key: 'site',
            dataIndex: 'site',
            align: 'right',
        },
    ];

    return (
            <div className='page'>
                <PageActions
                    backUrl='/settings'
                    title={`Gruppi Tag (${page?.data?.total})`}
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

export default Taxonomies;

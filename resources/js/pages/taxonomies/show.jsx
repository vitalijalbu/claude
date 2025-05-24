import React, { useState, useEffect } from 'react';
import {
    Button,
    Space,
    Table,
    Avatar,
    Tag,
} from 'antd';
import PageActions from '@/shared/components/page-actions';
import { IconCloudUpload, IconPencilMinus, IconTag, IconTrash } from '@tabler/icons-react';


const Show = ({props}) => {
    console.log('☘️ props:', props);
    const { page } = props;


    const columns = [
        {
            title: 'Nome',
            key: 'name',
            sortable: true,
            filterSearch: true,
            fixed: true,
            render: (record) => (
                    <Space>
                       <Avatar icon={<IconTag color='#1677ff'/>} />
                        {record?.name}
                    </Space>
            ),
        },
        {
            title: 'Gruppo',
            key: 'group',
            render: () => (
                <Tag>
                    {page?.name}
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
                    backUrl='/taxonomies'
                    title={page?.name}
                    extra={[
                        <Space>
                            <Button
                                type='primary'
                                icon={<IconPencilMinus />}
                            >
                                Modifica
                            </Button>  
                            <Button
                                danger
                                icon={<IconTrash />}
                            >
                                Elimina
                            </Button>
                        </Space>,
                    ]}
                />
                <div className='page-content'>
                <Table
                        columns={columns}
                        dataSource={page?.taxonomies}
                    />
                </div>
            </div>
    );
};

export default Show;

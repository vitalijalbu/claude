import React, { useState, useEffect } from 'react';
import {
    Row,
    Col,
    Card,
    Button,
    Space,
    List,
} from 'antd';
import PageActions from '@/shared/components/page-actions';
import { IconCloudUpload, IconPencilMinus, IconTrash } from '@tabler/icons-react';
import { useAtom } from 'jotai';
import { popupAtom } from '@/store/index';
import FormBody from '@/shared/profiles/form-body';
import { Table } from 'antd/lib';
import { listingColumns } from '@/shared/listings/columns';


const Show = ({props}) => {
    const { data, isLoading } = props;
    const [isOpen, setIsOpen] = useAtom(popupAtom);

    return (
            <div className='page'>
                <PageActions
                    backUrl='/profiles'
                    title={
                        <>
                            {' '}
                            Dettagli profilo - <mark>{data?.username}</mark>
                        </>
                    }
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
                    <Row gutter={[16, 16]}>
                        <Col span={18}>
                            <Card title='Dettagli profilo'>
                                <FormBody data={data} />
                            </Card>
                        </Col>
                        <Col span={6}>
                            <Card>
                                Data iscirzione
                            </Card>
                        </Col>
                    </Row>
                    <PageActions title={`Annunci (${data?.listings?.length})`}/>
                    <Row gutter={[16, 16]}>
                        <Col span={24}>
                        <Card>
                            <Table dataSource={data?.listings} columns={listingColumns} />
                        </Card>
                    </Col>
                    </Row>
                </div>
            </div>
    );
};

export default Show;

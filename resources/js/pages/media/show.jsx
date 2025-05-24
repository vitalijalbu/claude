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
import { IconPencilMinus, IconTrash } from '@tabler/icons-react';
import { useAtom } from 'jotai';
import { popupAtom } from '@/store/index';
import FormBody from '@/shared/profiles/form-body';
import { Upload } from 'antd/lib';



const Show = ({props}) => {
    const { data, isLoading } = props;
    const [isOpen, setIsOpen] = useAtom(popupAtom);

    return (
            <div className='page'>
                <PageActions
                    backUrl='/listings'
                    title={
                        <>
                            Dettagli annuncio - <mark>{data?.title}</mark>
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
                            <Card title='Dettagli' className='mb-4'>
                                <FormBody data={data} />
                            </Card>
                            <Card title='Foto'>
                                <Upload/>
                            </Card>
                        </Col>
                        <Col span={6}>
                            <Card>
                                <FormBody data={data} />
                            </Card>
                        </Col>
                    </Row>
                </div>
            </div>
    );
};

export default Show;

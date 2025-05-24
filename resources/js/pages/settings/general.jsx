import React, { useState, useEffect } from 'react';
import { Button, Card,     
    Form,
    Input,
    message,
    Col,
    Row,
    InputNumber, } from 'antd';
    import { useForm } from '@inertiajs/react';
import PageActions from '@/shared/components/page-actions';


const General = ({props}) => {
    const { page } = props;
    const initialData = page;
    const [form] = Form.useForm();
    const [formTouched, setFormTouched] = useState(false);
    const { data, setData, post, put, processing } = useForm({
        name: initialData?.name || '',
        website: initialData?.website || '',
        email: initialData?.email || '',
        percentage: initialData?.percentage || '',
        notes: initialData?.notes || '',
    });

    // Handle form submission
    const handleSubmit = () => {
        const url = initialData?.id
            ? `/profiles/${initialData?.id}`
            : '/suppliers';

        const method = initialData ? put : post; 

        method(url, {
            onSuccess: (res) => {
                console.log('res', res);
                message.success(res?.message);
                onClose();
            },
            onError: (res) => {
                console.log('res', res);
                message.error(res?.message);
            },
        });
    };


    return (
            <div className='page'>
                <PageActions
                    backUrl='/settings'
                    title='Impostazioni generali'
                />
                <div className='page-content'>
                <Card title='Impostazioni generali'>
                <Form
                layout='vertical'
                name='form-supplier'
                form={form}
                onFinish={handleSubmit}
                onValuesChange={(changedValues) => {
                    setFormTouched(true);
                    setData((prev) => ({ ...prev, ...changedValues }));
                }}
                disabled={processing}
                initialValues={data}
            >
                <Form.Item
                    label='Nome'
                    name='name'
                    rules={[
                        { required: true, message: 'Il campo è obbligatorio' },
                    ]}
                >
                    <Input
                        allowClear
                        placeholder='Inserisci il nome del profilo'
                    />
                </Form.Item>

                <Row gutter={16}>
                    <Col span={12}>
                        <Form.Item
                            label='Sito web'
                            name='website'
                            rules={[
                                {
                                    type: 'url',
                                    message: 'Inserisci un URL valido',
                                },
                            ]}
                        >
                            <Input
                                allowClear
                                placeholder='https://example.com'
                            />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item
                            label='Email'
                            name='email'
                            rules={[
                                {
                                    type: 'email',
                                },
                            ]}
                        >
                            <Input
                                allowClear
                                placeholder='Inserisci l/email del profilo'
                            />
                        </Form.Item>
                    </Col>
                </Row>
                <Form.Item
                    label='Percentuale prezzo'
                    name='percentage'
                    rules={[
                        {
                            pattern: /^\d+(\.\d+)?$/,
                            message: 'Inserisci un valore numerico valido',
                        },
                        { required: true, message: 'Il campo è obbligatorio' },
                    ]}
                >
                    <InputNumber
                        allowClear
                        placeholder='Inserisci una percentuale (es. 10.5)'
                    />
                </Form.Item>
            </Form>
                            </Card>
                </div>
            </div>
    );
};

export default General;

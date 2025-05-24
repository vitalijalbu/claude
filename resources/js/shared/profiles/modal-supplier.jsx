"use client";

import React, { useState, useEffect } from "react";
import {
    Button,
    Modal,
    Form,
    Input,
    message,
    Col,
    Row,
    InputNumber,
} from "antd";
import { useForm } from "@inertiajs/react";
import { IconPercentage } from "@tabler/icons-react";

const { TextArea } = Input;

const ModalProfile = ({ isOpened, onClose, initialData = {} }) => {
    const [form] = Form.useForm();
    const [formTouched, setFormTouched] = useState(false);
    const { data, setData, post, put, processing } = useForm({
        name: initialData?.name || "",
        website: initialData?.website || "",
        email: initialData?.email || "",
        percentage: initialData?.percentage || "",
        notes: initialData?.notes || "",
    });

    // Handle form submission
    const handleSubmit = () => {
        const url = initialData?.id
            ? `/profiles/${initialData?.id}`
            : "/suppliers";

        const method = initialData ? put : post; 

        method(url, {
            onSuccess: (res) => {
                console.log("res", res);
                message.success(res?.message);
                onClose();
            },
            onError: (res) => {
                console.log("res", res);
                message.error(res?.message);
            },
        });
    };

    return (
        <Modal
            open={isOpened}
            transitionName="ant-modal-slide-up"
            centered
            maskClosable={!formTouched || !processing}
            width={600}
            onCancel={onClose}
            title={initialData ? "Modifica profilo" : "Nuovo profilo"}
            footer={
                <div style={{ display: "flex", gap: "12px" }}>
                    <Button block type="default" onClick={onClose}>
                        Chiudi
                    </Button>
                    <Button
                        type="primary"
                        block
                        form="form-supplier"
                        htmlType="submit"
                        aria-label="Salva"
                        loading={processing}
                        disabled={!formTouched || processing}
                    >
                        Salva
                    </Button>
                </div>
            }
        >
            <Form
                layout="vertical"
                name="form-supplier"
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
                    label="Nome"
                    name="name"
                    rules={[
                        { required: true, message: "Il campo è obbligatorio" },
                    ]}
                >
                    <Input
                        allowClear
                        placeholder="Inserisci il nome del profilo"
                    />
                </Form.Item>

                <Row gutter={16}>
                    <Col span={12}>
                        <Form.Item
                            label="Sito web"
                            name="website"
                            rules={[
                                {
                                    type: "url",
                                    message: "Inserisci un URL valido",
                                },
                            ]}
                        >
                            <Input
                                allowClear
                                placeholder="https://example.com"
                            />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item
                            label="Email"
                            name="email"
                            rules={[
                                {
                                    type: "email",
                                    message: "Inserisci un'email valida",
                                },
                            ]}
                        >
                            <Input
                                allowClear
                                placeholder="Inserisci l'email del profilo"
                            />
                        </Form.Item>
                    </Col>
                </Row>
                <Form.Item
                    label="Percentuale prezzo"
                    name="percentage"
                    rules={[
                        {
                            pattern: /^\d+(\.\d+)?$/,
                            message: "Inserisci un valore numerico valido",
                        },
                        { required: true, message: "Il campo è obbligatorio" },
                    ]}
                >
                    <InputNumber
                        allowClear
                        placeholder="Inserisci una percentuale (es. 10.5)"
                        suffix={<IconPercentage color="#A1A8B0"/>}
                    />
                </Form.Item>

                 <Form.Item label="Bio" name="bio">
                                    <TextArea rows={6} placeholder="Biografia" allowClear />
                                </Form.Item>
            </Form>
        </Modal>
    );
};

export default ModalProfile;

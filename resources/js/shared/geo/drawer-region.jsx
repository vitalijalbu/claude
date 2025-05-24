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
    Drawer,
} from "antd";
import { useForm } from "@inertiajs/react";
import { IconPercentage } from "@tabler/icons-react";

const { TextArea } = Input;

const DrawerRegion = ({ isOpened, onClose, initialData = {} }) => {
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
            ? `/geo/${initialData?.id}`
            : "/geo";

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
        <Drawer
            open={isOpened}
            onClose={onClose}
            maskClosable={!formTouched || !processing}
            width={600}
            onCancel={onClose}
            title={initialData ? "Modifica regione" : "Nuovo regione"}
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
                        placeholder="Inserisci il nome del regione"
                    />
                </Form.Item>
            </Form>
        </Drawer>
    );
};

export default DrawerRegion;

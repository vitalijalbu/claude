import React, { useState, useEffect } from "react";
import { Form, InputNumber, Button, Flex } from "antd";
import { IconDeviceFloppy, IconCircleMinus, IconPlus } from "@tabler/icons-react";

const FormBody = ({props}) => {
    const [form] = Form.useForm();

    const onFinish = (values) => {
        console.log("Received values:", values);
    };

    return (
        <Form form={form} onFinish={onFinish} autoComplete="off" layout="vertical">
            <Form.List name="form-pricing">
                {(fields, { add, remove }) => (
                    <>
                        {fields.map(({ key, name, ...restField }) => (
                            <Flex
                                key={key}
                                gap={16}
                                align="center"
                                dividers="vertical"
                                justify="space-between"
                            >
                                <Form.Item
                                    {...restField}
                                     label="Qtà minima"
                                    name={[name, "min_qty"]}
                                    key={[key, "min_qty"]}
                                    rules={[
                                        {
                                            required: true,
                                            message: "Min Qty is required",
                                        },
                                    ]}
                                >
                                    <InputNumber placeholder="Min Qty" />
                                </Form.Item>
                                <Form.Item
                                    {...restField}
                                    label="Qtà massima"
                                    name={[name, "max_qty"]}
                                    key={[key, "max_qty"]}
                                >
                                    <InputNumber placeholder="Max Qty (facoltativo)" />
                                </Form.Item>
                                <Form.Item
                                    {...restField}
                                    label="Prezzo per unità"
                                    name={[name, "price_per_unit"]}
                                    key={[key, "price_per_unit"]}
                                    rules={[
                                        {
                                            required: true,
                                            message: "Price is required",
                                        },
                                    ]}
                                >
                                    <InputNumber
                                        placeholder="Price per Unit"
                                        step="0.01"
                                    />
                                </Form.Item>
                                <Button type="text" danger icon={<IconCircleMinus onClick={() => remove(name)} />}>Rimuovi</Button>    
                            </Flex>
                        ))}
                        <Form.Item>
                            <Button
                               color="default" 
                               variant="dashed"
                                onClick={() => add()}
                                icon={<IconPlus />}
                            >
                                Aggiungi prezzo
                            </Button>
                        </Form.Item>
                    </>
                )}
            </Form.List>

            <Form.Item>
                <Button color="default" variant="solid" htmlType="submit" icon={<IconDeviceFloppy/>} disabled={form.isFieldsTouched}>
                    Salva listino prezzi
                </Button>
            </Form.Item>
        </Form>
    );
};

export default FormBody;

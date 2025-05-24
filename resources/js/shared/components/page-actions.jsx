import React, { useState, useEffect } from "react";
import { Row, Col, Button, Space, Typography } from "antd";
import { IconChevronLeft } from "@tabler/icons-react";
import { Link } from "@inertiajs/react";
const { Text, Title } = Typography;

const PageActions = (props) => {
    return (
        <div className={`page-heading mb-1`}>
            <Row align="middle" justify="space-between" key={`row-` + 0}>
                <Col key={`col-` + 0}>
                    <div className="flex gap-2 items-center">
                        {props.backUrl && (
                            <Link href={props.backUrl}>
                                <Button
                                    icon={
                                        <IconChevronLeft className="anticon" />
                                    }
                                />
                            </Link>
                        )}
                        <div>
                            <Title level={3}>{props.title}</Title>
                            {props.subTitle && (
                                <div>
                                    <Text type="secondary">
                                        {props.subTitle}
                                    </Text>
                                </div>
                            )}
                        </div>
                    </div>
                </Col>
                <Col
                    flex="auto"
                    style={{ textAlign: "right" }}
                    key={`col-` + 1}
                >
                    <Space>{props.extra}</Space>
                </Col>
            </Row>
            {props.children && (
                <Row className="mt-1" key={`row-` + 1}>
                    {React.Children.map(props.children, (child, index) => (
                        <div key={index} style={{width: "100%"}}>
                            {child}
                        </div>
                    ))}
                </Row>
            )}
        </div>
    );
};

export default PageActions;

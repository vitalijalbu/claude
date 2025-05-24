import React, { useState, useEffect } from 'react';
import { Row, Col, Card, Typography, Avatar, Divider } from 'antd';
const { Title, Paragraph } = Typography;
import PageActions from '@/shared/components/page-actions';
import {
    IconBox,
    IconLanguage,
    IconSettings,
    IconTag,
    IconUsersGroup,
    IconWorld,
} from '@tabler/icons-react';
import { Link } from '@inertiajs/react';

const Index = ({props}) => {
    const { data } = props;

    // Array di sezioni con link
    const sections = [
        {
            title: 'Generale',
            links: [
                {
                    label: 'Impostazioni Generali',
                    icon: <IconSettings color='#1677ff' />,
                    url: '/settings/general',
                }, 
                {
                    label: 'Geo',
                    icon: <IconWorld color='#1677ff' />,
                    url: '/geo',
                },
                {
                    label: 'Siti',
                    icon: <IconLanguage color='#1677ff' />,
                    url: '/settings/sites',
                },
            ],
        },
        {
            title: 'Annunci',
            links: [
                {
                    label: 'Categorie',
                    icon: <IconBox color='#0e8f37' />,
                    url: '/categories',
                }, 
                {
                    label: 'Gruppi Tag',
                    icon: <IconTag color='#0e8f37' />,
                    url: '/taxonomies',
                },
            ],
        },
        {
            title: 'Utenti',
            links: [
                {
                    label: 'Utenti',
                    icon: <IconUsersGroup color='#fe5c5c' />,
                    url: '/settings/users',
                },
            ],
        },
    ];

    return (
        <div className='page'>
            <PageActions title='Impostazioni' />
            <div className='page-content'>
                <Row gutter={[16, 16]}>
                    {sections.map((section, index) => (
                        <Col span={24} key={index}>
                            <Divider orientation='left'>{section.title}</Divider> 
                            <Row gutter={[16, 16]}>
                                {section.links.map((link, i) => (
                                    <Col
                                        span={6}
                                        xl={6}
                                        lg={6}
                                        md={8}
                                        sm={24}
                                        xs={24}
                                        key={i}
                                    >
                                        <Link href={link.url}>
                                            <Card>
                                                <Avatar icon={link.icon} />
                                                <Title level={5}>{link.label}</Title>
                                            </Card>
                                        </Link>
                                    </Col>
                                ))}
                            </Row>
                        </Col>
                    ))}
                </Row>
            </div>
        </div>
    );
};

export default Index;

import React, { useState, useEffect } from 'react';
import { Row, Col, Card, Statistic } from 'antd';
import PageActions from '@/shared/components/page-actions';
import {
    IconBox,
    IconClock,
    IconUsersGroup,
} from '@tabler/icons-react';
import { Link } from '@inertiajs/react';

const Index = ({props}) => {
    const { data } = props;
    
    const navLinks = [
        {
            label: 'Totale Annunci',
            value: data?.total_listings || 0,
            icon: <IconBox />,
            url: '/listings',
        },
        {
            label: 'Totale Profili',
            value: data?.total_profiles || 0,
            icon: <IconBox />,
            url: '/profiles',
        },
        {
            label: 'Totale Categorie',
            value: data?.total_categories || 0,
            icon: <IconUsersGroup />,
            url: '/categories',
        }
    ];

    return (
        <div className='page'>
            <PageActions title='Dashboard' />
            <div className='page-content'>
                <Row gutter={[16, 16]}>
                    {navLinks.map((item, i) => (
                        <Col
                            span={6}
                            xl={6}
                            lg={6}
                            md={6}
                            sm={24}
                            xs={24}
                            key={i}
                        >
                            <Card>
                                <Link href={item.url}>
                                    <Statistic
                                        title={item.label}
                                        value={item.value}
                                        prefix={item.icon}
                                    />
                                </Link>
                            </Card>
                        </Col>
                    ))}
                </Row>
            </div>
        </div>
    );
};

export default Index;

import React from "react";
import { dateTimeFormatter } from "@/helpers/formatter";
import { Link } from "@inertiajs/react";
import { IconCloudUpload, IconDots, IconEye } from "@tabler/icons-react";
import { Avatar, Button, Space } from "antd";

export const listingColumns = [
    {
        title: "Titolo",
        key: "title",
        sortable: true,
        filterSearch: true,
        fixed: true,
        render: (record) => (
            <Link href={`/listings/${record?.id}`}>
                <Space>
                    <Avatar src={record.media ? `/images/${record?.logo}` : '/images/placeholder.svg'} />
                    {record?.title}
                </Space>
            </Link>
        ),
    }, 
    {
        title: "Profilo",
        key: "profile",
        sortable: true,
        filterSearch: true,
        fixed: true,
        render: (record) => (
            <Link href={`/profiles/${record.profile?.username}`} target="_blank">
                <Space>
                    <Avatar src={record.media ? `/images/${record?.logo}` : '/images/placeholder.svg'} />
                    {record?.profile?.username}
                </Space>
            </Link>
        ),
    },
    {
        title: "Categoria",
        key: "category",
        dataIndex: ["category", "title"],
    },
    {
        title: "Creato il",
        key: "created_at",
        type: "datetime",
        align: "right",
        sorter: (a, b) => a.created_at - b.created_at,
        render: (record) => (
            <span>{dateTimeFormatter(record?.created_at)}</span>
        ),
    },
    {
        key: "actions",
        align: "right",
        render: (record) => (
                <Button
                    type="text"
                    icon={<IconDots />}
                    onClick={() => toggleModal(record)}
               />
        ),
    }
];
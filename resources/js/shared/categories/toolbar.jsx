import React from "react";
import { useRouter } from "next/router";
import { Menu } from "antd";
import { Link } from "@inertiajs/react";

const Toolbar = () => {
    const { url, id } = usePage();

    const navLinks = [
        {
            key: "/profiles/[id]",
            label: <Link href={`/suppliers${id}`}>Details</Link>,
        },
        {
            key: "/profiles/[id]/categories",
            label: (
                <Link href={`/suppliers${id}/rows`}>
                    Item Rows
                </Link>
            ),
        },
    ];

    return (
        <Menu
            mode="horizontal"
            className="transparent"
            items={navLinks}
            selectedKeys={[router.pathname]}
        />
    );
};

export default Toolbar;

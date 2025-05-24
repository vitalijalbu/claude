import React, { Children } from "react";
import { Link, usePage } from "@inertiajs/react";
import {
    IconHome,
    IconUsersGroup,
    IconListDetails,
    IconSettings,
    IconGraph,
    IconTags,
    IconCategory,
    IconChartArcs,
    IconWorld,
    IconLanguage,
    IconUsers,
    IconLock,
    IconLibraryPhoto,
} from "@tabler/icons-react";
import { Divider, Menu } from "antd";

const SideNav = () => {
    const defaultSelected = usePage().url;
    const items = [
        {
            key: "/",
            icon: <IconHome />,
            label: <Link href="/">Dashboard</Link>,
        },
        {
            type: "group",
            label: "GESTISCI",
        },
        {
            key: "/profiles",
            icon: <IconUsersGroup />,
            label: <Link href="/profiles">Profili</Link>,
        },
        {
            key: "/listings",
            icon: <IconListDetails />,
            label: <Link href="/listings">Annunci</Link>,
        },
        {
            key: "/manage",
            icon: <IconTags color='#0e8f37'/>,
            label: "Gestisci",
            children: [
                {
                    key: "/categories",
                    icon: <IconCategory color='#0e8f37'/>,
                    label: <Link href="/categories">Categorie</Link>,
                },
                {
                    key: "/taxonomies",
                    icon: <IconTags color='#0e8f37'/>,
                    label: <Link href="/taxonomies">Tassonomie</Link>,
                },
                {
                    key: "/geo",
                    icon: <IconWorld color='#0e8f37'/>,
                    label: <Link href="/geo">Geo</Link>,
                },
            ]
        },
        {
            key: "/media",
            icon: <IconLibraryPhoto color='#0e8f37'/>,
            label: <Link href="/media">Media</Link>,
        },
        {
            type: "group",
            label: "UTENTI",
        },
        {
            key: "/users",
            icon: <IconUsers color='#fe5c5c'/>,
            label: <Link href="/users">Utenti</Link>,
        }, 
        {
            key: "/user/roless",
            icon: <IconLock color='#fe5c5c'/>,
            label: <Link href="/users/roles">Ruoli</Link>,
        }, 
        {
            type: "group",
            label: "REPORTS",
        },
        {
            key: "/reports",
            icon: <IconChartArcs />,
            label: <Link href="#">Analisi</Link>,
        },
        {
            type: "group",
            label: "ALTRO",
        },
        {
            key: "/settings/sites",
            icon: <IconLanguage />,
            label: <Link href="/settings/sites">Siti</Link>,
        },
        {
            key: "/settings",
            icon: <IconSettings />,
            label: <Link href="/settings">Impostazioni</Link>,
        }
    ];

    return (
        <aside>
            <div className="w-full content-center text-center h-[40px] font-semibold">
                OnlyEscorts
            </div>
            <Divider className="!m-0"/>
            <Menu
                mode="inline"
                defaultSelectedKeys={defaultSelected}
                items={items}
            />
        </aside>
    );
};

export default SideNav;

import { createInertiaApp } from "@inertiajs/react";
import * as React from "react";
import { createRoot } from "react-dom/client";
import "../assets/app.css";
import AppLayout from "./layouts/app-layout.jsx";
import { ConfigProvider } from "antd";
import theme from "../assets/theme.json";
import dayjs from "dayjs";
import 'dayjs/locale/it';
import it_IT from 'antd/locale/it_IT';

const appName = import.meta.env.VITE_APP_NAME;

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    progress: {
        color: "#1677ff",
        showSpinner: true,
    },
    resolve: name => {
        const pages = import.meta.glob('./pages/**/*.jsx', { eager: true });
        let page = pages[`./pages/${name}.jsx`];
    
        // Check if the page exists
        if (!page) {
            console.error(`Page not found: ./pages/${name}.jsx`);
            return null; // Or return a default page or error page component
        }
    
        // Check if the page has a default export
        if (!page.default) {
            console.error(`Page does not have a default export: ./pages/${name}.jsx`);
            return null; // Or return a default page or error page component
        }
    
        page.default.layout = (pageProps) => {
            const user = pageProps?.props?.auth.user;
            return user ? (
                <AppLayout>
                    <page.default key="auth" {...pageProps} />
                </AppLayout>
            ) : (
                <page.default key="not-auth" {...pageProps} />
            );
        };
    
        return page;
    },    
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(
            <ConfigProvider theme={theme} locale={it_IT}>
                    <App {...props} />
            </ConfigProvider>
        );
    }
});

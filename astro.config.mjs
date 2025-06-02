import { defineConfig } from 'astro/config';
import tailwindcss from '@tailwindcss/vite';
import node from '@astrojs/node';
import sitemap from '@astrojs/sitemap';
import alpinejs from '@astrojs/alpinejs';

// https://astro.build/config
export default defineConfig({
    site: 'http://it.onlyescort.vip',
    output: 'server',
    adapter: node({
        mode: 'standalone',
    }),
    server: {
        host: true,
    },
    i18n: {
        locales: ['it', 'en'],
        defaultLocale: 'it',
    },
    vite: {
        plugins: [tailwindcss()],
    },
    integrations: [sitemap(), alpinejs()],
    image: {
        layout: 'responsive',
    },
    devToolbar: {
        enabled: false,
    },
});
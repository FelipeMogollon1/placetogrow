import './bootstrap';
import '../css/app.css';


import { createApp, h } from 'vue';
import { createI18n } from 'vue-i18n';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'
import en from './Locales/en.json';
import es from './Locales/es.json';

const appName = import.meta.env.VITE_APP_NAME || 'Bootcamp PHP';

const i18n = createI18n({
    locale: 'es',
    fallbackLocale: 'es',
    legacy: false,
    messages: {
        es,
        en
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(LaravelPermissionToVueJS)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

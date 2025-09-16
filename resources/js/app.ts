import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

import ElementPlus from 'element-plus';
import ElementTiptapPlugin from 'element-tiptap';
// import ElementTiptap's styles
import 'element-tiptap/lib/style.css';

// Vuetify
import '@mdi/font/css/materialdesignicons.css';
import { createVuetify } from 'vuetify';
import { en, es } from 'vuetify/locale';
import 'vuetify/styles';

import { VDateInput } from 'vuetify/labs/VDateInput';

import i18n from './plugins/i18n'; // Adjust path as needed

// Register Vuetify as plugin
const vuetify = createVuetify({
    components: {
        VDateInput,
    },
    locale: {
        locale: 'es', // or 'en-CA'
        fallback: 'en',
        messages: { es, en },
    },
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(i18n)
            .use(ElementPlus)
            .use(ElementTiptapPlugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

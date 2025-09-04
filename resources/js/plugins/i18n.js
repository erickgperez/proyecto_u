// src/plugins/i18n.js
import { createI18n } from 'vue-i18n';

// Import your locale files
import en from '../locales/en.json';
import es from '../locales/es.json';

const i18n = createI18n({
    locale: 'es', // Default locale
    fallbackLocale: 'en', // Fallback locale
    messages: {
        es,
        en,
    },
});

export default i18n;

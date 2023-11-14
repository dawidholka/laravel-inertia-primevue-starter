import { createApp, h } from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import PrimeVue from 'primevue/config';
import ToastService from "primevue/toastservice";
import pl from './Locales/pl.json';
import en from './Locales/en.json';
import { createI18n } from 'vue-i18n';
import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";
import '../scss/app.scss';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const i18n = createI18n({
    locale: 'pl',
    messages: {
        en: en,
        pl: pl
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(PrimeVue)
            .use(i18n)
            .use(ToastService)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

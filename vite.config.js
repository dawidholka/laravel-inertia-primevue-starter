import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import inertiaLayout from "momentum-layout";
import autoimport from "unplugin-auto-import/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.ts',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        inertiaLayout(),
        autoimport({
            vueTemplate: true,
            dts: "resources/scripts/types/auto-imports.d.ts",
            imports: [
                "vue",
                {"@inertiajs/vue3": ["router", "useForm", "usePage"]},
            ],
        })
    ],
    assetsInclude: ["resources/images/**/*"],
    resolve: {
        alias: {
            'App': '/src/App/Resources',
            'Landlord': '/src/Landlord/Resources',
        }
    }
});

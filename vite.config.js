import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    /*resolve: {
        alias: {
            '': path.resolve(__dirname, '')
        }
    },*/
    plugins: [
        laravel({
            input: [
                'resources/css/bootstrap.min.css',
                'resources/css/bootstrap-icons.css',
                // 'resources/css/app.css',
                // 'resources/js/assets/bootstrap.min.js',
                'resources/js/app.js',
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
    ],
});

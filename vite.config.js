import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import collectModuleAssetsPaths from './vite-module-loader.js';

const modulePaths = await collectModuleAssetsPaths(
    ['resources/css/app.css', 'resources/js/app.js'],
    'Modules'
);

export default defineConfig({
    plugins: [
        laravel({
            input: modulePaths,
            refresh: true,
        }),
        tailwindcss(),
    ],
});

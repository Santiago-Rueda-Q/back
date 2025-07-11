import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    test: {
        environment: 'jsdom',
        globals: true,
        setupFiles: './tests/setup.js',
        coverage: {
            reporter: ['text', 'json', 'html'],
            exclude: ['resources/js/app.js'],
        },
    },
});

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
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

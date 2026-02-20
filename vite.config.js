import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/login.css', 'resources/css/register.css', 'resources/css/home.css', 'resources/css/history.css', 'resources/css/create-session.css', 'resources/css/session.css', 'resources/css/session-completed.css', 'resources/css/password-reset.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

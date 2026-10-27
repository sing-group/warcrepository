import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';

// Sustituye a gulpfile.js (Laravel Elixir): compila los mismos ficheros,
// ahora en public/build.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/assets/sass/app.scss', 'resources/assets/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            // Versión de Vue con compilador de plantillas, como usaba Elixir.
            vue: 'vue/dist/vue.esm.js',
            // Rutas "~bootstrap-sass/..." de los .scss, como las resolvía webpack.
            '~bootstrap-sass': fileURLToPath(new URL('./node_modules/bootstrap-sass', import.meta.url)),
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 3 usa sintaxis Sass antigua: se compila igual, sin avisos.
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'slash-div'],
            },
        },
    },
});

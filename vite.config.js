import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Admin / Auth layout (Bootstrap only)
                'resources/sass/app.scss',
                'resources/js/app.js',

                // Public LTR theme (en, fr)
                'resources/sass/app-ltr.scss',
                'resources/js/theme-ltr.js',

                // Public RTL theme (fa/Persian)
                'resources/sass/app-rtl.scss',
                'resources/js/theme-rtl.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        // Target modern browsers — avoids polyfilling ES2020+ features unnecessarily.
        // Lighthouse flagged legacy JS (Object.getPrototypeOf shims) as 8 KiB wasted.
        target: ['es2020', 'chrome80', 'safari14', 'firefox78'],
        // Inline small assets (< 4 KB) as base64 to reduce HTTP requests
        assetsInlineLimit: 4096,
        // Separate CSS per JS chunk
        cssCodeSplit: true,
        sourcemap: false,
        rolldownOptions: {
            transform: {
                inject: {
                    $: 'jquery',
                    jQuery: 'jquery',
                    'window.jQuery': 'jquery',
                },
            },
            output: {
                // Split vendor bundles for long-term caching
                manualChunks(id) {
                    if (id.includes('node_modules/jquery')) return 'vendor-jquery';
                    if (id.includes('node_modules/bootstrap')) return 'vendor-bootstrap';
                    if (id.includes('node_modules')) return 'vendor';
                },
            },
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'if-function'],
            },
        },
    },
    resolve: {
        alias: {
            // Make $ and jQuery available globally for plugins that rely on it
            jquery: 'jquery',
        },
    },
});

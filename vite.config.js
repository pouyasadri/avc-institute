import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import inject from '@rollup/plugin-inject';

export default defineConfig({
    plugins: [
        inject({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
        }),
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
        // Inline small assets (< 4 KB) as base64 to reduce HTTP requests
        assetsInlineLimit: 4096,
        // Separate CSS per JS chunk
        cssCodeSplit: true,
        sourcemap: false,
        rollupOptions: {
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
        // Vite 8 uses LightningCSS by default. Enable error recovery so that
        // IE6-era star-property hacks (* zoom, *display) in older vendor CSS
        // (Owl Carousel, animate.css) are silently stripped rather than crashing
        // the build. These properties are already ignored by all modern browsers.
        lightningcss: {
            errorRecovery: true,
        },
    },
    resolve: {
        alias: {
            // Make $ and jQuery available globally for plugins that rely on it
            jquery: 'jquery',
        },
    },
});

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';

function getTemplate() {
    try {
        const content = fs.readFileSync('config/site.php', 'utf-8');
        const match = content.match(/'template'\s*=>\s*'([^']+)'/);
        return match ? match[1] : 'bento';
    } catch (e) {
        return 'bento';
    }
}

const template = getTemplate();

// Only enable HTTPS in dev if the cert files actually exist (local Laragon setup)
function getHttpsConfig() {
    const keyPath  = 'C:/laragon/etc/ssl/laragon.key';
    const certPath = 'C:/laragon/etc/ssl/laragon.crt';
    if (fs.existsSync(keyPath) && fs.existsSync(certPath)) {
        return { key: fs.readFileSync(keyPath), cert: fs.readFileSync(certPath) };
    }
    return undefined;
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                `resources/views/templates/${template}/css/app.css`,
                `resources/views/templates/${template}/js/app.js`,
            ],
            refresh: [
                `resources/views/templates/${template}/**`,
                'routes/**',
                'config/site.php',
            ],
        }),
        tailwindcss(),
    ],

    build: {
        // Raise chunk warning threshold (Tailwind CSS output can be large)
        chunkSizeWarningLimit: 600,

        rollupOptions: {
            output: {
                // Split vendor JS into a separate cached chunk
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                },
                // Content-hash filenames for long-term caching
                entryFileNames:  'assets/[name]-[hash].js',
                chunkFileNames:  'assets/[name]-[hash].js',
                assetFileNames:  'assets/[name]-[hash][extname]',
            },
        },

        // Minify with esbuild (default, fast) — explicitly set for clarity
        minify: 'esbuild',

        // Generate source maps only in dev; omit for production bundles
        sourcemap: false,

        // Target modern browsers — reduces polyfill bloat on mobile
        target: ['es2020', 'chrome80', 'firefox78', 'safari14'],
    },

    server: {
        host: 'lozand.local',
        cors: true,
        https: getHttpsConfig(),
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

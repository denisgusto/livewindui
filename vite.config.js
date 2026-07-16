import { defineConfig } from 'vite'

// Bundle de runtime da lib: um IIFE auto-executável servido via @livewindScripts
// (não é um módulo importado pelo consumidor). Saída commitada em dist/livewind.js.
export default defineConfig({
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        minify: true,
        lib: {
            entry: 'js/index.js',
            name: 'Livewind',
            formats: ['iife'],
            fileName: () => 'livewind.js',
        },
    },
})

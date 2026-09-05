import { defineConfig } from 'vite';
// Optional Vite build. PHP serves precompiled assets without a Node server.
export default defineConfig({
  base: './',
  build: {
    outDir: 'public_assets/build', emptyOutDir: true, cssCodeSplit: true,
    rollupOptions: {
      input: { app: 'public_assets/js/app.js', styles: 'resources/css/app.css' },
      output: { entryFileNames: '[name].js', assetFileNames: '[name][extname]' }
    }
  }
});

import { defineConfig } from 'vite';
import leaf from '@leafphp/vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    tailwindcss(),
    leaf({
      input: ['app/views/css/app.css', 'app/views/css/new.css'],
      refresh: true,
    }),
  ],
});

import { defineConfig, loadEnv } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '')

  return {
    server: {
      host: true,
      port: 5173,
      strictPort: true,
      origin: 'https://' + env.VITE_DEV_SERVER_DOCKER_HOST_NAME,
      cors: true,
    },
    plugins: [
      laravel({
        input: ['resources/css/app.css', 'resources/js/app.ts'],
        refresh: true,
      }),
      tailwindcss(),
    ],
  }
})

module.exports = {
  apps: [
    {
      name: "knotelle-backend",
      cwd: "/var/www/knottele-fullstack/backend",
      script: "artisan",
      interpreter: "php",
      args: "serve --host=127.0.0.1 --port=8000",
      env: {
        APP_ENV: "production",
      },
      autorestart: true,
      restart_delay: 3000,
      max_restarts: 10,
    },
    {
      name: "knotelle-frontend",
      cwd: "/var/www/knottele-fullstack/frontend",
      script: "node_modules/next/dist/bin/next",
      interpreter: "node",
      args: "start -p 3000",
      env: {
        NODE_ENV: "production",
        PORT: "3000",
        INTERNAL_API_URL: "http://127.0.0.1:8000/api",
        NEXT_PUBLIC_API_URL: "/knottele/api",
      },
      autorestart: true,
      restart_delay: 3000,
      max_restarts: 10,
    },
  ],
};

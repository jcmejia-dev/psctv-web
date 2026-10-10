# Deploy PSCTV to Vercel

This is a Laravel application, with Vite building assets into `public/build`.
The root `vercel.json` serves `public` and routes application requests to
`api/index.php` using the community PHP 8.2 runtime.

## Project settings

- Root Directory: repository root (leave empty).
- Framework Preset: Other.
- Node.js Version: 22.x.
- Build Command: `npm run build`.
- Output Directory: `public` (remove the old `dist` override).
- Install Command: default (`npm install`).

Push these changes to the branch connected to Vercel, then redeploy.
The PHP runtime installs Composer dependencies; do not add `composer install`
to the Node build command.

## Environment variables

Set these in Vercel before deploying:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://YOUR-PROJECT.vercel.app
APP_KEY=YOUR-LARAVEL-APPLICATION-KEY
LOG_CHANNEL=stderr
DB_CONNECTION=mysql
DB_HOST=YOUR-HOSTED-DATABASE-HOST
DB_PORT=3306
DB_DATABASE=YOUR-DATABASE
DB_USERNAME=YOUR-DATABASE-USER
DB_PASSWORD=YOUR-DATABASE-PASSWORD
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
```

Generate a new production key with `php artisan key:generate --show`, then
paste its output into `APP_KEY`. Keep the key stable across redeployments.
Do not commit `.env` or credentials.

Login and dashboards require a reachable hosted MySQL database with the
application schema. Run migrations from a trusted local environment against
that database after reviewing them. This checkout does not include the original
database data, and its migrations may not reconstruct the entire old schema.

For a homepage preview without a database, use `SESSION_DRIVER=cookie` and
`CACHE_STORE=array`. This does not enable working accounts or dashboards.

Vercel temporary storage is not persistent. Uploaded photos and documents need
external object storage; database sessions remain available across function
instances. Scheduled billing and background workers need a separate execution
setup. Configure Google OAuth, Stripe, Pusher, and mail credentials if those
features are part of the demo. Review the public debug routes before publishing
the complete application.

Runtime documentation: https://github.com/vercel-community/php

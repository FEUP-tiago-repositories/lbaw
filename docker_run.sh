#!/usr/bin/env bash
# -------------------------------------------------------------------
# docker_run.sh — Entrypoint script for LBAW Laravel + Nginx container
#
# Generates .env at runtime from environment variables injected by
# the deployment platform (e.g. Railway), then starts Laravel + Nginx.
# -------------------------------------------------------------------
set -euo pipefail

cd /var/www

# Generate .env from environment variables injected by the platform
cat > /var/www/.env <<EOF
APP_NAME="${APP_NAME:-SportsHub}"
APP_ENV=production
APP_KEY="${APP_KEY}"
APP_DEBUG=false
APP_URL="${APP_URL:-http://localhost}"
ASSET_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT:-5432}"
DB_SCHEMA="${DB_SCHEMA:-public}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

BROADCAST_DRIVER=log
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync

BROADCAST_CONNECTION=pusher

PUSHER_APP_ID="${PUSHER_APP_ID:-}"
PUSHER_APP_KEY="${PUSHER_APP_KEY:-}"
PUSHER_APP_SECRET="${PUSHER_APP_SECRET:-}"
PUSHER_HOST="${PUSHER_HOST:-}"
PUSHER_PORT="${PUSHER_PORT:-443}"
PUSHER_SCHEME="${PUSHER_SCHEME:-https}"
PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER:-eu}"

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY:-}"
VITE_PUSHER_HOST="${PUSHER_HOST:-}"
VITE_PUSHER_PORT="${PUSHER_PORT:-443}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME:-https}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER:-eu}"

GOOGLE_CLIENT_ID="${GOOGLE_CLIENT_ID:-}"
GOOGLE_CLIENT_SECRET="${GOOGLE_CLIENT_SECRET:-}"
GOOGLE_CALL_BACK_ROUTE="${GOOGLE_CALL_BACK_ROUTE:-}"

FACEBOOK_CLIENT_ID="${FACEBOOK_CLIENT_ID:-}"
FACEBOOK_CLIENT_SECRET="${FACEBOOK_CLIENT_SECRET:-}"
FACEBOOK_CALL_BACK_ROUTE="${FACEBOOK_CALL_BACK_ROUTE:-}"
EOF

# Ensure Laravel runtime directories exist
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Run database migrations automatically
php artisan migrate --force || echo "Migration failed"

# Clear and cache Laravel configuration for faster boot
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

php artisan cache:clear || echo "Cache clear failed"

# Rebuild optimized caches
php artisan config:cache
php artisan route:cache || echo "Route cache failed"
php artisan view:cache || echo "View cache failed"

# Start PHP-FPM in background
php-fpm -D

# Start nginx in foreground (keeps container alive)
exec nginx -g "daemon off;"

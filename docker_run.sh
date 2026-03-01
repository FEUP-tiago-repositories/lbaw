#!/usr/bin/env bash
# -------------------------------------------------------------------
# docker_run.sh — Entrypoint script for LBAW Laravel + Nginx container
#
# This script prepares Laravel's caches for production
# and starts both PHP-FPM (background) and Nginx (foreground).
# -------------------------------------------------------------------
set -euo pipefail

cd /var/www

# Generate .env from environment variables injected by Railway (or any other platform)
cat > /var/www/.env <<EOF
APP_NAME="${APP_NAME:-Thingy}"
APP_ENV=production
APP_KEY="${APP_KEY}"
APP_DEBUG=false
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync
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

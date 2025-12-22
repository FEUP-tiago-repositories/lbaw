#!/usr/bin/env bash
# -------------------------------------------------------------------
# docker_run.sh — Entrypoint script for LBAW Laravel + Nginx container
#
# This script prepares Laravel’s caches for production
# and starts both PHP-FPM (background) and Nginx (foreground).
# -------------------------------------------------------------------
set -euo pipefail

cd /var/www

# Ensure Laravel runtime directories exist
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

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
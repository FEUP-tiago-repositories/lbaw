#!/usr/bin/env bash
# -----------------------------------------------------------------------
# docker_run.sh — Entrypoint for LBAW Laravel + Nginx on Railway
# NOTE: -u removed so unbound variables don't crash the script
# -----------------------------------------------------------------------
set -eo pipefail

cd /var/www

echo "========================================"
echo " LBAW Container Startup"
echo "========================================"

# -----------------------------------------------------------------------
# Parse DATABASE_URL into individual vars for Laravel
# Railway sets DATABASE_URL as: postgres://user:pass@host:port/dbname
# OR:                            postgresql://user:pass@host:port/dbname
# -----------------------------------------------------------------------
if [ -n "${DATABASE_URL:-}" ]; then
    echo "[DB] DATABASE_URL detected, parsing..."

    _URL="${DATABASE_URL}"
    _URL="${_URL#postgres://}"
    _URL="${_URL#postgresql://}"

    DB_USERNAME="${_URL%%:*}"
    _REST="${_URL#*:}"
    DB_PASSWORD="${_REST%%@*}"
    _REST="${_REST#*@}"
    DB_HOST="${_REST%%:*}"
    _REST="${_REST#*:}"
    DB_PORT="${_REST%%/*}"
    DB_DATABASE="${_REST#*/}"
    DB_DATABASE="${DB_DATABASE%%\?*}"

    export DB_USERNAME DB_PASSWORD DB_HOST DB_PORT DB_DATABASE
    echo "[DB] Host=${DB_HOST} Port=${DB_PORT} DB=${DB_DATABASE} User=${DB_USERNAME}"
else
    echo "[DB] No DATABASE_URL found. Using individual DB_* vars."
    echo "[DB] Host=${DB_HOST:-UNSET} Port=${DB_PORT:-5432} DB=${DB_DATABASE:-UNSET} User=${DB_USERNAME:-UNSET}"
fi

# -----------------------------------------------------------------------
# Configure Nginx to listen on Railway's dynamic PORT
# Railway injects PORT env var. If not set, default to 80.
# -----------------------------------------------------------------------
APP_PORT="${PORT:-80}"
echo "[NGINX] Configuring Nginx to listen on port ${APP_PORT}..."
sed -i "s/listen 80;/listen ${APP_PORT};/g" /etc/nginx/conf.d/default.conf
echo "[NGINX] Port set to ${APP_PORT}."

# -----------------------------------------------------------------------
# Generate .env at runtime from environment variables
# -----------------------------------------------------------------------
echo "[ENV] Writing .env file..."
cat > /var/www/.env <<EOF
APP_NAME="${APP_NAME:-SportsHub}"
APP_ENV=production
APP_KEY="${APP_KEY:-}"
APP_DEBUG=false
APP_URL="${APP_URL:-http://localhost}"
ASSET_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST="${DB_HOST:-}"
DB_PORT="${DB_PORT:-5432}"
DB_SCHEMA="${DB_SCHEMA:-lbaw25122}"
DB_DATABASE="${DB_DATABASE:-}"
DB_USERNAME="${DB_USERNAME:-}"
DB_PASSWORD="${DB_PASSWORD:-}"

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
echo "[ENV] .env written."

# Ensure Laravel runtime directories
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# -----------------------------------------------------------------------
# Seed database on first deploy
# -----------------------------------------------------------------------
SCHEMA="${DB_SCHEMA:-lbaw25122}"
echo "[SEED] Target schema: ${SCHEMA}"

if [ -n "${DATABASE_URL:-}" ]; then
    PSQL_CONN="${DATABASE_URL}"
else
    PSQL_CONN="postgresql://${DB_USERNAME:-}:${DB_PASSWORD:-}@${DB_HOST:-}:${DB_PORT:-5432}/${DB_DATABASE:-}"
fi

echo "[SEED] Testing database connection..."
if PGSSLMODE=prefer psql "${PSQL_CONN}" -c "SELECT 1;" > /dev/null 2>&1; then
    echo "[SEED] Connection OK."

    TABLE_EXISTS=$(PGSSLMODE=prefer psql "${PSQL_CONN}" -tAc \
        "SELECT COUNT(*) FROM information_schema.tables \
         WHERE table_schema = '${SCHEMA}' AND table_name = 'user';" 2>&1)

    echo "[SEED] Table 'user' in schema '${SCHEMA}': count=${TABLE_EXISTS}"

    if [ "${TABLE_EXISTS}" = "0" ] || [ -z "${TABLE_EXISTS}" ]; then
        echo "[SEED] Table not found. Running sportshub-seed.sql..."
        PGSSLMODE=prefer psql "${PSQL_CONN}" -v ON_ERROR_STOP=1 \
            -f /var/www/database/sportshub-seed.sql
        echo "[SEED] Seed completed successfully!"
    else
        echo "[SEED] Table 'user' already exists. Skipping seed."
    fi
else
    echo "[SEED] ERROR: Cannot connect to the database!"
    echo "[SEED] DATABASE_URL set: $([ -n "${DATABASE_URL:-}" ] && echo YES || echo NO)"
fi

# -----------------------------------------------------------------------
# Laravel cache
# -----------------------------------------------------------------------
echo "[LARAVEL] Clearing caches..."
php artisan config:clear  || true
php artisan route:clear   || true
php artisan view:clear    || true
php artisan cache:clear   || true

echo "[LARAVEL] Rebuilding caches..."
php artisan config:cache
php artisan route:cache   || echo "[LARAVEL] Route cache failed (acceptable)"
php artisan view:cache    || echo "[LARAVEL] View cache failed (acceptable)"

echo "[STARTUP] Starting PHP-FPM..."
php-fpm -D

echo "[STARTUP] Starting Nginx on port ${APP_PORT}..."
exec nginx -g "daemon off;"

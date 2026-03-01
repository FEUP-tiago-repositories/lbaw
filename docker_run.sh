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

    # Strip scheme (handles both postgres:// and postgresql://)
    _URL="${DATABASE_URL}"
    _URL="${_URL#postgres://}"
    _URL="${_URL#postgresql://}"
    # _URL is now: user:password@host:port/database?params

    DB_USERNAME="${_URL%%:*}"
    _REST="${_URL#*:}"                  # password@host:port/database?params
    DB_PASSWORD="${_REST%%@*}"
    _REST="${_REST#*@}"                 # host:port/database?params
    DB_HOST="${_REST%%:*}"
    _REST="${_REST#*:}"                 # port/database?params
    DB_PORT="${_REST%%/*}"
    DB_DATABASE="${_REST#*/}"           # database?params
    DB_DATABASE="${DB_DATABASE%%\?*}"  # strip ?sslmode=... etc.

    export DB_USERNAME DB_PASSWORD DB_HOST DB_PORT DB_DATABASE
    echo "[DB] Host=${DB_HOST} Port=${DB_PORT} DB=${DB_DATABASE} User=${DB_USERNAME}"
else
    echo "[DB] No DATABASE_URL found. Using individual DB_* vars."
    echo "[DB] Host=${DB_HOST:-UNSET} Port=${DB_PORT:-5432} DB=${DB_DATABASE:-UNSET} User=${DB_USERNAME:-UNSET}"
fi

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
DB_SCHEMA="${DB_SCHEMA:-public}"
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

# -----------------------------------------------------------------------
# Ensure Laravel runtime directories exist with correct permissions
# -----------------------------------------------------------------------
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# -----------------------------------------------------------------------
# Seed database on first deploy
# Uses DATABASE_URL directly with psql to avoid SSL / parsing issues.
# Checks if the target schema already exists before running.
# -----------------------------------------------------------------------
SCHEMA="${DB_SCHEMA:-public}"
echo "[SEED] Target schema: ${SCHEMA}"

# Build psql connection string
# Prefer DATABASE_URL directly (Railway handles SSL automatically in the URL)
if [ -n "${DATABASE_URL:-}" ]; then
    PSQL_CONN="${DATABASE_URL}"
else
    PSQL_CONN="postgresql://${DB_USERNAME:-postgres}:${DB_PASSWORD:-}@${DB_HOST:-localhost}:${DB_PORT:-5432}/${DB_DATABASE:-postgres}"
fi

echo "[SEED] Testing database connection..."
if PGSSLMODE=prefer psql "${PSQL_CONN}" -c "\\conninfo" > /dev/null 2>&1; then
    echo "[SEED] Connection OK."

    # Check if schema already has the 'user' table (means seed was already run)
    SCHEMA_EXISTS=$(PGSSLMODE=prefer psql "${PSQL_CONN}" -tAc \
        "SELECT schema_name FROM information_schema.schemata WHERE schema_name = '${SCHEMA}';" 2>&1)

    echo "[SEED] Schema check result: '${SCHEMA_EXISTS}'"

    if [ -z "${SCHEMA_EXISTS}" ]; then
        echo "[SEED] Schema '${SCHEMA}' not found. Running seed SQL..."
        PGSSLMODE=prefer psql "${PSQL_CONN}" -v ON_ERROR_STOP=1 \
            -f /var/www/database/sportshub-seed.sql
        echo "[SEED] Seed completed successfully!"
    else
        echo "[SEED] Schema '${SCHEMA}' already exists. Skipping seed."
    fi
else
    echo "[SEED] ERROR: Cannot connect to the database!"
    echo "[SEED] Check DB_HOST / DB_PORT / DB_USERNAME / DB_PASSWORD / DB_DATABASE."
    echo "[SEED] DB Host  = ${DB_HOST:-UNSET}"
    echo "[SEED] DB Port  = ${DB_PORT:-UNSET}"
    echo "[SEED] DB Name  = ${DB_DATABASE:-UNSET}"
    echo "[SEED] DB User  = ${DB_USERNAME:-UNSET}"
    # Do not print the password for security
fi

# -----------------------------------------------------------------------
# Laravel cache commands
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

echo "[STARTUP] Starting Nginx..."
exec nginx -g "daemon off;"

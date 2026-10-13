#!/usr/bin/env bash
set -Eeuo pipefail

trap 'echo "[boot] ERRO na linha $LINENO: $BASH_COMMAND" >&2' ERR

cd /var/www

echo "[boot] A iniciar SportsHub"

for name in APP_KEY APP_URL DB_CONNECTION DB_URL DB_SCHEMA; do
    if [[ -n "${!name:-}" ]]; then
        echo "[boot] $name: definido"
    else
        echo "[boot] $name: EM FALTA"
    fi
done

echo "[boot] A preparar diretórios e logs"

mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

touch \
    /var/log/php-fpm-error.log \
    /var/log/php-fpm-access.log \
    storage/logs/php-error.log \
    storage/logs/laravel.log

chown -R www-data:www-data storage bootstrap/cache

echo "[boot] A remover configuração compilada antiga"
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes*.php

# Encaminha os ficheiros de logs para stdout do container.
tail -n 0 -F \
    /var/log/php-fpm-error.log \
    /var/log/php-fpm-access.log \
    storage/logs/php-error.log \
    storage/logs/laravel.log &

echo "[boot] A verificar configuração do PHP-FPM"
php-fpm -t

echo "[boot] A verificar configuração do nginx"
nginx -t

echo "[boot] A iniciar PHP-FPM"
php-fpm -D

echo "[boot] A iniciar nginx na porta 80"
exec nginx -g "daemon off;"
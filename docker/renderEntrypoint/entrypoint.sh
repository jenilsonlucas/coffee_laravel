#!/bin/bash
set -e

if ! grep -q "APP_KEY=" /app/.env || grep -q "APP_KEY=$" /app/.env; then
    echo "Generating Laravel APP_KEY..."
    php artisan key:generate --force
fi

envsubst '${PORT}' < /app/docker/nginx/renderTemplate/nginx.conf.template > /etc/nginx/conf.d/default.conf


chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

# Create storage symlink
php artisan storage:link || true

# Inicia PHP-FPM em background
php-fpm -D

# Inicia Nginx no foreground
nginx -g "daemon off;"

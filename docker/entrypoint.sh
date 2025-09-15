#!/bin/bash
set -e

# Wait for database
#echo "Waiting for database..."
#until php -r "try { new PDO('pgsql:host=laravel_postgres;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); exit(0); } catch (Exception \$e) { exit(1); }"; do
#  sleep 3
#done


if ! grep -q "APP_KEY=" /app/.env || grep -q "APP_KEY=$" /app/.env; then
    echo "Generating Laravel APP_KEY..."
    php artisan key:generate --force
fi

envsubst '${PORT}' < /app/docker/nginx.conf.template > /etc/nginx/conf.d/default.conf

# if [ "$APP_ENV" = "production" ]; then
#   echo "Rodando migrations no modo produção..."
#   php artisan migrate:refresh --force
#   php artisan db:seed --force

# fi

# Run migrations + seed

chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

# Create storage symlink
php artisan storage:link || true

# Inicia PHP-FPM em background
php-fpm -D

# Inicia Nginx no foreground
nginx -g "daemon off;"
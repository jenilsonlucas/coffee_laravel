#!/bin/bash
set -e

# Wait for database
echo "Waiting for database..."
until php -r "try { new PDO('pgsql:host=laravel_postgres;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); exit(0); } catch (Exception \$e) { exit(1); }"; do
  sleep 3
done

# Run migrations + seed
php artisan migrate --force
php artisan db:seed --force

# Create storage symlink
php artisan storage:link || true

# Start PHP-FPM
exec "$@"
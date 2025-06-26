#!/bin/bash

echo "Fixing permissions..."
chown -R www-data:www-data /app/storage /app/bootstrap/cache /app
chmod -R 775 /app/storage /app/bootstrap/cache /app

exec "$@"
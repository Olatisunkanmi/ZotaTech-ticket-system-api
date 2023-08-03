#!/usr/bin/env bash
echo "Running composer"
composer self-update --2
# composer global require hirak/prestissimo
composer install --ignore-platform-reqs --no-dev --working-dir=/var/www/html/

echo "Clear cache"
php artisan route:clear
php artisan cache:clear
# composer  -o dump-autoload

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Passport install..."
php artisan passport:install

echo "Storage Link..."
php artisan storage:link
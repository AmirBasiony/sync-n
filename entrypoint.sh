#!/bin/sh
set -e

# echo "Waiting for MySQL at $DB_HOST:$DB_PORT..."
# until mysqladmin ping -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --silent; do
#     sleep 2
# done
DB_HOST=${DB_HOST:-mysql}
DB_PORT=${DB_PORT:-3306}

echo "Waiting for MySQL to be ready..."
until mysql -h mysql -u root -proot -e "SELECT 1;" >/dev/null 2>&1; do
  sleep 2
done

echo "MySQL is ready. Starting Laravel..."

exec php artisan serve --host=0.0.0.0 --port=8000

# echo "🚀 Starting PHP-FPM for Laravel..."
# exec php-fpm
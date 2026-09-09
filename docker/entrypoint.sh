#!/bin/bash
set -e

cd /var/www/html

if [ ! -f .env ]; then
    echo "No .env found — creating one from .env.example..."
    cp .env.example .env
fi

# Ensure vendor matches composer.lock (named volume may lag behind image builds)
if [ ! -f vendor/autoload.php ] || [ ! -d vendor/nunomaduro/collision ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Host bind-mount may have stale package discovery from a different vendor tree
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php
php artisan package:discover --ansi >/dev/null 2>&1 || true

mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
if [ -f .env ]; then
    chmod ug+rw .env || true
fi

DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-staffpanel_db}"
DB_USERNAME="${DB_USERNAME:-staffpanel}"
DB_PASSWORD="${DB_PASSWORD:-secret}"

echo "Waiting for MySQL at ${DB_HOST}..."
until php -r "
try {
    new PDO(
        'mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}',
        '${DB_USERNAME}',
        '${DB_PASSWORD}',
        [PDO::ATTR_TIMEOUT => 2]
    );
    exit(0);
} catch (Throwable \$e) {
    exit(1);
}
"; do
    sleep 2
done
echo "MySQL is up."

# Prefer APP_KEY from the container environment; only write one if missing in .env
if [ -z "${APP_KEY}" ] && ! grep -qE '^APP_KEY=base64:.+' .env; then
    php artisan key:generate --force
fi

php artisan config:clear >/dev/null 2>&1 || true
php artisan route:clear >/dev/null 2>&1 || true
php artisan view:clear >/dev/null 2>&1 || true

exec "$@"

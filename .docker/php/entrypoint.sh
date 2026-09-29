#!/bin/bash
# bash and not sh: sh (dash) removes the variables with a dot (database.default.database...) given by docker-compose.yml
# Start of the PHP container: dependencies, database (migrations + seed), then php-fpm.
set -e
cd /var/www/html

# Dependencies (the code is mounted from the host: vendor/ is created there the first time)
if [ ! -f vendor/autoload.php ]; then
    echo "[usgg] composer install"
    composer install --no-interaction --no-progress --prefer-dist
fi

# Folders written by the site
mkdir -p writable/cache writable/logs writable/session writable/uploads writable/debugbar \
         public/uploads/pp public/uploads/events public/uploads/sections public/uploads/testimonials

# Database: new migrations at each start, test data only in an empty database (see DatabaseSeeder)
echo "[usgg] migrations"
# spark migrate ends without error code even when it fails: its output is checked
output=$(php spark migrate --all 2>&1) || true
echo "$output" | grep -v "Deprecated"
if ! echo "$output" | grep -q "Migrations complete"; then
    echo "[usgg] ERREUR : les migrations ont échoué" >&2
    exit 1
fi
if [ "${USGG_SEED:-true}" = "true" ]; then
    echo "[usgg] seed"
    php spark db:seed DatabaseSeeder
fi

exec "$@"

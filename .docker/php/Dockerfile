FROM php:8.2-fpm

# System libraries of the PHP extensions (sqlite3 and pdo_sqlite are already in the image)
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libicu-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype-dev libwebp-dev \
    && rm -rf /var/lib/apt/lists/*

# intl: dates / validation, gd: photos, logos and profile pictures, zip: composer, mysqli / pdo_mysql: to use MySQL instead of SQLite
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" intl gd zip mysqli pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PHP settings (uploads, timezone) and php-fpm settings (environment variables given to the site)
COPY ./.docker/php/conf.d/usgg.ini /usr/local/etc/php/conf.d/usgg.ini
COPY ./.docker/php/docker.conf /usr/local/etc/php-fpm.d/zzz-docker.conf

# Installs the dependencies, migrates and seeds the database, then starts php-fpm
COPY ./.docker/php/entrypoint.sh /usr/local/bin/usgg-entrypoint
RUN chmod +x /usr/local/bin/usgg-entrypoint

WORKDIR /var/www/html/
ENTRYPOINT ["usgg-entrypoint"]
CMD ["php-fpm", "-F", "-R"]

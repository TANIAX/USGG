FROM php:8.2-fpm

# Install package dependencies
RUN apt-get install debian-archive-keyring \
    && apt-get update \
    && apt-get install -y cifs-utils && apt-get install -y iputils-ping \
    && apt-get update && apt-get install -y unzip libaio1 wget \
    && apt-get install sqlite3 libsqlite3-dev

# Run php as root
COPY ./.docker/php/docker.conf /usr/local/etc/php-fpm.d/zzz-docker.conf

# PHP extensions
RUN \
    docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure mysqli \
    && docker-php-ext-install pdo_mysql \
    && apt-get install -y libicu-dev \ 
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \ 
    && apt-get update && apt-get install -y libldap2-dev \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install ldap

# install sqlite
RUN docker-php-ext-configure pdo_sqlite \
&& docker-php-ext-install pdo_sqlite


WORKDIR /var/www/html/
CMD bash -c "php-fpm -F -R"
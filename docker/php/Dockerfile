FROM php:8.1.7-fpm-bullseye

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install -yqq --no-install-recommends \
    curl \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    libxml2-dev \
    unzip \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug redis \
    &&  rm -rf /tmp/pear \
    && docker-php-ext-install fileinfo intl pdo_mysql soap sockets zip gd \
    && docker-php-ext-enable xdebug redis

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY conf/* /usr/local/etc/php/conf.d/

WORKDIR /var/www/blog
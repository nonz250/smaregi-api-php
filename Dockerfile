FROM php:8.1-fpm-alpine

WORKDIR /var/www/app

COPY php.ini /usr/local/etc/php
COPY --from=composer /usr/bin/composer /usr/bin/composer

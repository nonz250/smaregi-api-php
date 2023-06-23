FROM php:8.1-fpm-alpine

WORKDIR /var/www/app

RUN set -eux && \
    apk update && \
    apk add --no-cache --virtual=.build-dependencies \
        autoconf \
        gcc \
        g++ \
        linux-headers \
        make \
        && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del .build-dependencies

COPY php.ini /usr/local/etc/php
COPY --from=composer /usr/bin/composer /usr/bin/composer

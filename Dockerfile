FROM php:8-cli-alpine

WORKDIR /etc/rector

RUN apk add --no-cache patch \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

RUN echo "memory_limit=2G" > /usr/local/etc/php/conf.d/99-local.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN mkdir -p /etc/rector

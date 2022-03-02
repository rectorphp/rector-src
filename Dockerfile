FROM php:8-cli-alpine

WORKDIR /etc/rector

RUN apk add --no-cache patch \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN mkdir -p /etc/rector

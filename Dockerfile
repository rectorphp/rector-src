FROM php:8.2-cli-alpine

WORKDIR /etc/rector

# required for composer patches
RUN apk add --no-cache patch git

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN mkdir -p /etc/rector
RUN git config --global --add safe.directory /etc/rector

# Install xdebug for debugging in IDE:
RUN apk add --update linux-headers && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

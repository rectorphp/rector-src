FROM php:8-cli-alpine

WORKDIR /etc/rector

# required for composer patches
RUN apk add --no-cache patch git

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN mkdir -p /etc/rector
RUN git config --global --add safe.directory /etc/rector

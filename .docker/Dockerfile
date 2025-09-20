# base
FROM php:8.3-fpm-alpine AS base
RUN apk add --no-cache git zip unzip curl bash icu-dev oniguruma-dev libzip-dev libpng-dev \
 && docker-php-ext-install pdo pdo_mysql intl zip \
 && docker-php-ext-enable opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

# dev image (with Node for asset build)
FROM base AS dev
RUN apk add --no-cache nodejs npm
CMD ["php-fpm"]

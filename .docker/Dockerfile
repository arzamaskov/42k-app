# ========================
# Base (PHP-FPM + Composer)
# ========================
FROM php:8.3-fpm-alpine AS base
RUN apk add --no-cache git zip unzip curl bash icu-dev oniguruma-dev libzip-dev libpng-dev \
 && docker-php-ext-install pdo pdo_mysql intl zip \
 && docker-php-ext-enable opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

# ========================
# Dev image (with Node)
# ========================
FROM base AS dev
RUN apk add --no-cache nodejs npm
CMD ["php-fpm"]

# ========================
# Assets build (Node only)
# ========================
FROM node:20-alpine AS assets
WORKDIR /app
# кешируем зависимости
COPY app/package*.json ./
RUN npm ci
# копируем код для сборки фронта
COPY app ./
RUN npm run build
# результат: /app/public/build

# ========================
# Production image
# ========================
FROM base AS prod
WORKDIR /var/www/html

# 1) Composer deps (кеш слоёв), без скриптов
COPY app/composer.json app/composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# 2) Код приложения
COPY app ./

# 3) Готовые фронт-ассеты из assets-стейджа
COPY --from=assets /app/public/build ./public/build

# 4) Допрогон composer (включит скрипты) + права
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rw storage bootstrap/cache

CMD ["php-fpm"]

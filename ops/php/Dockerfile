# ========================
# Base (PHP-FPM + Composer)
# ========================
FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
      git zip unzip curl bash icu-dev oniguruma-dev libzip-dev libpng-dev postgresql-dev \
  && docker-php-ext-install pdo pdo_mysql pdo_pgsql intl zip gd \
  && docker-php-ext-enable opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ========================
# Dev image (PHP + Node)
# ========================
FROM base AS dev
RUN apk add --no-cache nodejs npm
# dev-образ оставляем «тонким»: код монтируем в docker-compose
CMD ["php-fpm"]

# ========================
# Assets build (Node only)
# ========================
FROM node:20-alpine AS assets
WORKDIR /app
# кешируем node-зависимости
COPY package*.json ./
RUN npm ci
# копируем проект (для vite: resources/, vite.config.js и пр.)
COPY . .
RUN npm run build
# результат: /app/public/build

# ========================
# Production image (php-fpm)
# ========================
FROM base AS prod
WORKDIR /var/www/html

# 1) кеш Composer слоёв (без скриптов)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# 2) код приложения
COPY . .

# .env в образ не кладём
RUN rm -f .env || true

# 3) готовые фронт-ассеты
COPY --from=assets /app/public/build ./public/build

# 4) финальный composer + права
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
 && mkdir -p storage/framework/{cache,session,views} storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rw storage bootstrap/cache

# Примечание: artisan config:cache/route:cache лучше запускать на деплое,
# когда уже есть реальные ENV (APP_KEY, DB_*, и т.д.)
CMD ["php-fpm"]

# ========================
# Web (nginx, только для public/)
# ========================
FROM nginx:1.27-alpine AS web
WORKDIR /var/www/html
RUN mkdir -p /var/www/html/public
# кладём весь public (включая собранные build-ассеты)
COPY --from=assets /app/public /var/www/html/public
# конфиг nginx монтируется снаружи (volume), поэтому тут его не фиксируем


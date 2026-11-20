FROM php:8.3-cli AS php-deps

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev libzip-dev unzip \
    && docker-php-ext-install pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

FROM node:22-alpine AS frontend

WORKDIR /app/frontend
COPY frontend/package.json frontend/package-lock.json ./
RUN npm ci
COPY frontend/ ./
ARG VITE_API_URL=/api
RUN VITE_API_URL="${VITE_API_URL}" npm run build

FROM php-deps AS app

COPY . .
COPY --from=frontend /app/frontend/dist/ ./public/
RUN composer dump-autoload --no-dev --optimize \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data
EXPOSE 10000

CMD ["sh", "-c", "php artisan migrate --force && if [ \"${RUN_SEEDERS:-false}\" = \"true\" ]; then php artisan db:seed --force; fi && exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]

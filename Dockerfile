FROM serversideup/php:8.5-frankenphp-alpine AS vendor
USER root
RUN install-php-extensions intl
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
USER www-data
WORKDIR /var/www/html
COPY --chown=www-data:www-data . .
RUN composer install --no-dev --optimize-autoloader --no-interaction

FROM vendor AS assets
USER root
RUN apk add --no-cache nodejs npm
RUN --mount=type=cache,target=/root/.npm npm ci
RUN NODE_OPTIONS=--max-old-space-size=512 npm run build

FROM vendor AS production
COPY --from=assets --chown=www-data:www-data /var/www/html/public/build ./public/build

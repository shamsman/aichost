# Multi-stage production build for Google Cloud Run
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
COPY . .
RUN composer dump-autoload --optimize

FROM php:8.3-apache AS app
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    PORT=8080

RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev libpng-dev libonig-dev libxml2-dev unzip git curl \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath gd opcache \
    && a2enmod rewrite headers remoteip \
    && rm -rf /var/lib/apt/lists/*

# Copy Apache virtualhost config
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Copy application files
COPY --from=vendor /app /var/www/html
WORKDIR /var/www/html

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy and setup entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

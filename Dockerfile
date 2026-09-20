FROM php:8.3-apache

# Install required system packages and PHP extensions for Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cloud Run dynamic port configuration
ENV PORT=8080
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf \
    && a2enmod rewrite

# Copy custom Apache virtual host
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install Composer dependencies for production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissions
RUN chmod +x /var/www/html/docker/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]

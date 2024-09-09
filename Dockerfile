# Use the official PHP image as a base
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install dependencies using apk (Alpine package manager)
RUN apk --no-cache update && apk add --no-cache \
    build-base \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    bash

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Node.js and npm
RUN apk add --no-cache nodejs npm

# Install Composer - a multi-stage build step, which is used to copy the composer binary from the official Composer Docker image (composer:latest) into your container.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application code to the container
# COPY . /var/www # not required since it is done in docker-compose.yml >> php: >> volumes

# Set the appropriate permissions for the Laravel application
RUN chown -R www-data:www-data /var/www

# Set the user to www-data
USER www-data

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]

# Use the official PHP image as a base
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd \
    && apk --no-cache add nodejs npm

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application code to the container
COPY . /var/www

# Set the appropriate permissions for the Laravel application
RUN chown -R www-data:www-data /var/www

# Set the user to www-data
USER www-data

# Grants full read, write, and execute permissions to everyone (risky) for the entire /var/www directory structure recursively
# RUN chmod 777 -R /var/www

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]

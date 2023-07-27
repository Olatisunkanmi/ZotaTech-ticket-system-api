# Use the official PHP image with Apache as the base image
FROM php:8.1-apache


# Install system dependencies
RUN apt-get update && apt-get install -y libpng-dev libonig-dev libxml2-dev zip unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Set the working directory to Laravel app
WORKDIR /var/www/html

USER $user
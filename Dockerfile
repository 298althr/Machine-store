FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libzip-dev \
       unzip \
       git \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Environment variables for Composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy the entire application
COPY . .

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Ensure necessary directories and permissions
RUN mkdir -p data/db \
    && mkdir -p web/uploads/payments \
    && mkdir -p web/uploads/tracking \
    && mkdir -p web/uploads/agent_chat \
    && mkdir -p web/uploads/software-activation \
    && chmod -R 777 data \
    && chmod -R 777 web/uploads

# Change WORKDIR to web for runtime
WORKDIR /var/www/html/web

# Use PORT environment variable for Railway
ENV PORT=8080
EXPOSE 8080

# Start PHP built-in server from the web folder
# This ensures that index.php is in the root of the server
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} index.php"]

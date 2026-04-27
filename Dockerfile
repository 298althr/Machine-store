FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libzip-dev \
       unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy the entire application
COPY . .

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader || true

# Ensure necessary directories and permissions
RUN mkdir -p data/db \
    && mkdir -p web/uploads/payments \
    && mkdir -p web/uploads/tracking \
    && mkdir -p web/uploads/agent_chat \
    && mkdir -p web/uploads/software-activation \
    && chmod -R 777 data \
    && chmod -R 777 web/uploads

# Use PORT environment variable for Railway
ENV PORT=8080
EXPOSE 8080

# Start PHP built-in server
# When using -t web, the router script path should be relative to that directory
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t web index.php"]

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

# Copy the entire application
COPY . .

# Install dependencies (Remove || true to see actual errors in logs)
RUN composer install --no-interaction --no-dev --optimize-autoloader

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
# We run from the root, but use web/ as document root
CMD ["php", "-S", "0.0.0.0:8080", "-t", "web", "web/index.php"]

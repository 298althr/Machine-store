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

# Copy composer files
COPY composer.json ./
COPY composer.lock ./

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader || true

# Copy rest of application
COPY . .

# Create necessary directories and set permissions
RUN mkdir -p /var/www/html/data/db \
    && mkdir -p /var/www/html/web/uploads/payments \
    && mkdir -p /var/www/html/web/uploads/tracking \
    && mkdir -p /var/www/html/web/uploads/agent_chat \
    && mkdir -p /var/www/html/web/uploads/software-activation \
    && chmod -R 777 /var/www/html/data \
    && chmod -R 777 /var/www/html/web/uploads

# Create symlink for images directory
RUN ln -sf /var/www/html/images /var/www/html/web/images || true

# Use PORT environment variable for Railway (defaults to 8080)
ENV PORT=8080
EXPOSE 8080

# Use shell form to properly expand PORT variable
CMD ["sh", "-c", "cd web && php -S 0.0.0.0:${PORT:-8080} index.php"]

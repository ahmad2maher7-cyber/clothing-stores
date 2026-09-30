# ═══════════════════════════════════════════════════
#   Laravel 12 + PHP 8.3 + Nginx
#   Multi-stage Docker build for production
# ═══════════════════════════════════════════════════

# ─────────────── Stage 1: Build Frontend Assets ───────────────
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy source
COPY . .

# Build assets
RUN npm run build

# ─────────────── Stage 2: PHP Application ───────────────
FROM php:8.3-fpm-alpine

# Install system dependencies + PHP extensions
RUN apk add --no-cache \
        nginx \
        supervisor \
        curl \
        libpng-dev \
        libzip-dev \
        libxml2-dev \
        zip \
        unzip \
        oniguruma-dev \
        postgresql-dev \
        nodejs \
        npm \
        shadow \
        fcgi \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install composer dependencies (production)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# Copy application files
COPY . .

# Copy built assets from node-builder
COPY --from=node-builder /app/public/build ./public/build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Copy Supervisor config
COPY docker/supervisord.conf /etc/supervisord.conf

# Expose port (Render uses $PORT env var)
EXPOSE 8080

# Run startup script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
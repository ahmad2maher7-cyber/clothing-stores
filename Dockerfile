# ═══════════════════════════════════════════════════
#   Laravel 12/13 + PHP 8.3 + Nginx on Render
#   Optimized Multi-stage Docker Build
# ═══════════════════════════════════════════════════

# ─────────────── Stage 1: Build Frontend ───────────────
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm ci --no-audit --no-fund

# Copy all project files (respecting .dockerignore)
COPY . .

# Build assets
RUN npm run build

# ─────────────── Stage 2: PHP Runtime ───────────────
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
        icu-dev \
        nodejs \
        npm \
        shadow \
        fcgi \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ─── Copy composer files ───
COPY composer.json composer.lock ./

# ─── Install dependencies (with fallback) ───
RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --no-progress \
        --optimize-autoloader \
    || composer update \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --no-progress \
        --optimize-autoloader

# ─── Copy application ───
COPY . .

# ─── Copy built assets from Node stage ───
COPY --from=node-builder /app/public/build ./public/build

# ─── Set permissions ───
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# ─── Nginx config ───
COPY docker/nginx.conf /etc/nginx/nginx.conf

# ─── Supervisor config ───
COPY docker/supervisord.conf /etc/supervisord.conf

# ─── Startup script ───
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]
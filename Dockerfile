# ═══════════════════════════════════════════════════
#   Laravel + PHP 8.3 + Nginx on Render
# ═══════════════════════════════════════════════════

# ─────────────── Stage 1: Build Frontend ───────────────
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm ci --no-audit --no-fund

COPY . .
RUN npm run build

# ─────────────── Stage 2: PHP Runtime ───────────────
FROM php:8.4-fpm-alpine

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

# Install Composer (latest)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ─── Copy application FIRST ───
COPY . .

# ─── Copy built assets from Node stage ───
COPY --from=node-builder /app/public/build ./public/build

# ─── Configure Composer ───
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1

# ─── Install dependencies (no scripts) ───
RUN composer install \
        --no-dev \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts \
    && php artisan package:discover --ansi

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
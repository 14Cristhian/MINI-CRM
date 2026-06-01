FROM php:8.2-cli-alpine

# Patch known CVEs in base Alpine packages
RUN apk upgrade --no-cache

# System deps + gd dependencies
RUN apk add --no-cache \
    curl git zip unzip \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    libzip-dev libxml2-dev oniguruma-dev \
    nodejs npm

# PHP extensions (including gd with jpeg + freetype)
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
        xml \
        pcntl \
        opcache

# Composer v2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install PHP deps (prod) + build Vite assets
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm ci \
    && npm run build \
    && rm -rf node_modules

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan migrate --force \
    && (php artisan db:seed --force 2>/dev/null || true) \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}

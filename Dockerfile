FROM php:8.2-cli-alpine

# Patch known CVEs in base Alpine packages
RUN apk upgrade --no-cache

# System deps + gd dependencies
RUN apk add --no-cache \
    curl git zip unzip bash \
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
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install PHP deps (prod, no Faker) + build Vite assets
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm ci \
    && npm run build \
    && rm -rf node_modules

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Wait for MySQL, then migrate + seed + cache + serve
CMD bash -c '\
    echo "Waiting for MySQL..." && \
    for i in $(seq 1 15); do \
        php -r "new PDO(\"mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE\", \"$DB_USERNAME\", \"$DB_PASSWORD\");" 2>/dev/null && break; \
        echo "  attempt $i/15 — retrying in 5s..." && sleep 5; \
    done && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}'

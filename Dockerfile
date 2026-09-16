FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    bash \
    unzip \
    git

RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    bcmath \
    intl \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

FROM dunglas/frankenphp:1-php8.4

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN install-php-extensions zip

WORKDIR /app

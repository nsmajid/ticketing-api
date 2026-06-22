FROM dunglas/frankenphp:php8.4

RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    zip \
    intl \
    gd \
    opcache \
    redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
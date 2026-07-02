FROM dunglas/frankenphp:php8.4

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    zip \
    curl \
    && rm -rf /var/lib/apt/lists/*

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
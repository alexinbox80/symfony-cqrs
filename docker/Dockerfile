FROM php:8.3-fpm

# системные пакеты + зависимости для amqp
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpq-dev libonig-dev libxml2-dev \
    librabbitmq-dev pkg-config autoconf g++ make \
    && docker-php-ext-install intl pdo pdo_mysql opcache \
    # ставим ext-amqp
    && pecl install amqp \
    && docker-php-ext-enable amqp

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

FROM php:8.0-fpm
WORKDIR /app
COPY ./src /app
RUN docker-php-ext-install pdo pdo_mysql; \
    docker-php-ext-install mysqli && docker-php-ext-enable mysqli
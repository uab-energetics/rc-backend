FROM php:7.1

RUN apt-get update -y && apt-get install -y openssl mysql-client zip unzip && \
    docker-php-ext-install pdo pdo_mysql mbstring

RUN mkdir -p /.config/psysh /.composer && \
    chmod -R 777 /.config /.composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD php artisan serve --host 0.0.0.0 --port 8000
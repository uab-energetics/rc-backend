FROM php:7.1-apache

RUN apt-get update -y && apt-get install -y openssl zip unzip && \
    docker-php-ext-install pdo pdo_mysql mbstring && \
    a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/app

COPY composer.json ./
COPY composer.lock ./
RUN export COMPOSER_ALLOW_SUPERUSER=1 && \
    composer install --no-scripts --no-autoloader


COPY . /var/www/app

RUN export COMPOSER_ALLOW_SUPERUSER=1 && \
    composer dump-autoload -o && \
    groupmod -g 1000 www-data && \
    chown -R root:www-data . && \
    chmod -R 755 . && \
    chmod -R 775 storage && \
    chmod -R 775 bootstrap/cache

EXPOSE 80
FROM php:7.1-apache

RUN apt-get update -y && apt-get install -y openssl zip unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    docker-php-ext-install pdo pdo_mysql mbstring; \
    a2enmod rewrite

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/app

COPY --chown=root:www-data composer.json ./
COPY --chown=root:www-data composer.lock ./
RUN export COMPOSER_ALLOW_SUPERUSER=1 && \
    composer install --no-scripts --no-autoloader --no-dev

COPY --chown=root:www-data . .

RUN export COMPOSER_ALLOW_SUPERUSER=1 && \
    composer dump-autoload -o && \
    \
    chmod -R 755 . && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 80
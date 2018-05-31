FROM vectorapps/php:7.1-apache

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/app

COPY composer.json ./
COPY composer.lock ./
RUN export COMPOSER_ALLOW_SUPERUSER=1 && \
    composer install --no-scripts --no-autoloader --no-dev


COPY . .
RUN chown root:www-data -R .

RUN export COMPOSER_ALLOW_SUPERUSER=1 && \
    composer dump-autoload -o && \
    chmod -R 755 . && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["sh", "./scripts/start.sh"]
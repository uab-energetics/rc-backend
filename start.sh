while ! mysqladmin ping -h "$DB_HOST" --silent; do
    sleep 1
done

php artisan migrate --force

apache2ctl -D FOREGROUND
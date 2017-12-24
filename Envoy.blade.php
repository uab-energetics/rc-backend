@servers( ['web' => [ 'researchcoder.com' ]])

@task('deploy', ['on' => 'web'])
    cd /var/www/v3/api.researchcoder.com
    git pull origin master
    composer install
    php artisan migrate
@endtask
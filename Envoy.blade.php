@servers( ['web' => [ 'researchcoder.com' ]])

@task('deploy', ['on' => 'web'])
    cd /var/www/v3/api.researchcoder.com
    git pull origin master
    composer install
    php artisan migrate
@endtask

@finished
    @slack("https://hooks.slack.com/services/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", "#notifications", "An update to research coder API has been published!");
@endfinished
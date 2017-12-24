<?php $__container->servers( ['web' => [ 'chris@researchcoder.com' ]]); ?>

<?php $__container->startMacro('deploy' ['on' => 'web'); ?>
    git
    composer
    laravel
<?php $__container->endMacro(); ?>

<?php $__container->startTask('git'); ?>
    git pull origin master
<?php $__container->endTask(); ?>

<?php $__container->startTask('composer'); ?>
    composer install
<?php $__container->endTask(); ?>

<?php $__container->startTask('laravel'); ?>
    php artisan migrate
<?php $__container->endTask(); ?>
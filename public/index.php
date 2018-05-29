<?php

define('LARAVEL_START', microtime(true));


require __DIR__.'/../vendor/autoload.php';
require __DIR__ . '/../vendor/sentry/sentry/lib/Raven/Autoloader.php'; /* Sentry error reporting: https://docs.sentry.io/clients/php/ */

Raven_Autoloader::register();

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

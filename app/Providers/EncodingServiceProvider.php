<?php

namespace App\Providers;

use App\Services\Encodings\EncodingService;
use Illuminate\Support\ServiceProvider;

class EncodingServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(EncodingService::class, function ($app) {
            return new EncodingService();
        });
    }
}

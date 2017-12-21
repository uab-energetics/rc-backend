<?php

namespace App\Providers;

use App\Services\Publications\PublicationService;
use Illuminate\Support\ServiceProvider;

class PublicationServiceProvider extends ServiceProvider {
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
        $this->app->singleton(PublicationService::class, function ($app) {
            return new PublicationService();
        });
    }
}

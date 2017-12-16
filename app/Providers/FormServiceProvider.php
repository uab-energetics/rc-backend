<?php

namespace App\Providers;

use App\Services\FormService\FormService;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider {
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
        $this->app->singleton(FormService::class, function ($app) {
            return new FormService();
        });
    }
}

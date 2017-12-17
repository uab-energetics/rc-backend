<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Services\ProjectService\ProjectService;

class ProjectServiceProvider extends ServiceProvider {
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
        $this->app->singleton(ProjectService::class, function ($app) {
            return new ProjectService();
        });
    }
}
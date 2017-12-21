<?php

namespace App\Providers;

use App\Services\Encodings\AssignmentService;
use Illuminate\Support\ServiceProvider;

class AssignmentServiceProvider extends ServiceProvider {
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
        $this->app->singleton(AssignmentService::class, function ($app) {
            return new AssignmentService();
        });
    }
}

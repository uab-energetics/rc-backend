<?php

namespace App\Providers;

use App\Services\Comments\CommentService;
use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider {
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
        $this->app->singleton(CommentService::class, function ($app) {
            return new CommentService();
        });
    }
}

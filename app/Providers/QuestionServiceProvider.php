<?php

namespace App\Providers;

use App\Services\Questions\QuestionService;
use Illuminate\Support\ServiceProvider;

class QuestionServiceProvider extends ServiceProvider {
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
        $this->app->singleton(QuestionService::class, function ($app) {
            return new QuestionService();
        });
    }
}

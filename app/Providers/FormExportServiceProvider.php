<?php

namespace App\Providers;

use App\Services\Exports\FormExportService;
use Illuminate\Support\ServiceProvider;

class FormExportServiceProvider extends ServiceProvider {
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
        $this->app->singleton(FormExportService::class, function ($app) {
            return new FormExportService();
        });
    }
}

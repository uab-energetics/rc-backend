<?php

namespace App\Providers;

use App\Services\Repositories\PubRepoService;
use Illuminate\Support\ServiceProvider;

class PubRepoServiceProvider extends ServiceProvider {

    public function provides() {
        return [PubRepoService::class];
    }

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
        $this->app->singleton(PubRepoService::class, function ($app) {
            $host = config('custom.publication_service.host');
            return new PubRepoService($host);
        });
    }
}

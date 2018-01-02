<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Pusher\Pusher;

class PusherProvider extends ServiceProvider
{

    public function register() {
        $this->app->singleton(Pusher::class, function(){
            return new Pusher(
                config('pusher.key'),
                config('pusher.secret'),
                config('pusher.app_id'),
                config('pusher.options')
            );
        });
    }
}

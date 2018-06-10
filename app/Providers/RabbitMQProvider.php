<?php

namespace App\Providers;

use App\Services\RabbitMQ\RabbitMQService;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQProvider extends ServiceProvider {

    protected $defer = true;

    public function provides() {
        return [ RabbitMQService::class ];
    }

    public function boot() {}


    /**
     * Register the application services.
     * @return void
     */
    public function register() {
        $this->app->singleton(RabbitMQService::class, function ($app) {
            $connection = new AMQPStreamConnection(
                config('rabbitmq.connection.host'),
                config('rabbitmq.connection.port'),
                config('rabbitmq.connection.user'),
                config('rabbitmq.connection.password')
            );

            $channel = $connection->channel();

            register_shutdown_function(function() use ($connection, $channel) {
                $channel->close();
                $connection->close();
            });

            return new RabbitMQService(
                $channel,
                config('rabbitmq.exchanges'),
                config('rabbitmq.queues'),
                config('rabbitmq.bindings'),
                config('rabbitmq.handlers'),
                config('rabbitmq.default_overrides')
            );
        });
    }
}

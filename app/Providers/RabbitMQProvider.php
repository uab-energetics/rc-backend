<?php

namespace App\Providers;

use App\Messaging\RabbitPublisher;
use App\Services\RabbitMQ\RabbitMQService;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Channel\AMQPChannel;
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
            register_shutdown_function(function() use ($connection) {
                $connection->close();
            });
            $channel = $connection->channel();
            $publisher = new RabbitPublisher($channel);
            foreach (config('rabbitmq.exchanges') as $exchange => $type) {
                $channel->exchange_declare($exchange, $type, false, true, false);
            }
            return new RabbitMQService($channel);
        });
    }
}

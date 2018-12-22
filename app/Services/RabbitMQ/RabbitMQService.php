<?php


namespace App\Services\RabbitMQ;


use App\Services\RabbitMQ\Core\RabbitConsumer;
use App\Services\RabbitMQ\Core\RabbitMessage;
use App\Services\RabbitMQ\Core\RabbitMQOptions;
use App\Services\RabbitMQ\Core\RabbitPublisher;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService {

    public function publishMessage($messageData, $exchange = '', $routingKey = '', $options = []) {
        $options['exchange'] = $exchange;
        $options['routing_key'] = $routingKey;
        $payload = json_encode($messageData);
        $this->publisher->publishEvent($payload, $options);
    }

    public function registerHandler($queue, $handlerClass, $options = []) {
        $handler = app()->make($handlerClass);
        $options['queue'] = $queue;
        $callback = function (AMQPMessage $msg) use ($handler, $handlerClass) {
            $rabbitMsg = $this->makeRabbitMessage($msg);
            echo "$handlerClass: " .json_encode($rabbitMsg->payload(), JSON_PRETTY_PRINT) . PHP_EOL;
            $handler->handle($rabbitMsg);
        };
        return $this->consumer->registerCallback($callback, $options);
    }

    public function registerCallback($queue, $callback, $options = []) {
        $options['queue'] = $queue;
        $wrappedCallback = function (AMQPMessage $message) use ($callback) {
            $rabbitMsg = $this->makeRabbitMessage($message);
            $callback($rabbitMsg);
        };
        return $this->consumer->registerCallback($wrappedCallback, $options);
    }

    public function listen() {
        $this->consumer->listen();
    }

    public function declareExchange($exchange, $options = []) {
        $options = array_merge($this->exchangeOptions, $options);
        return $this->channel->exchange_declare(
            $exchange,
            $options['type'],
            $options['passive'],
            $options['durable'],
            $options['auto_delete'],
            $options['internal'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    public function declareQueue($options = []) {
        $options = array_merge($this->queueOptions, $options);
        return $this->channel->queue_declare(
            $options['queue'],
            $options['passive'],
            $options['durable'],
            $options['exclusive'],
            $options['auto_delete'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    public function bindQueue($queue, $exchange, $options = []) {
        $options = array_merge($this->bindOptions, $options);
        return $this->channel->queue_bind(
            $queue,
            $exchange,
            $options['routing_key'],
            $options['nowait'],
            $options['arguments'],
            $options['ticket']
        );
    }

    protected function declareExchanges($exchanges) {
        foreach ($exchanges as $exchange => $options) {
            $this->declareExchange($exchange, $options);
        }
    }

    protected function declareQueues($queues) {
        foreach ($queues as $queue => $options) {
            $options['queue'] = $queue;
            $this->declareQueue($options);
        }
    }

    protected function bindQueues($bindings) {
        foreach ($bindings as $binding) {
            $queue = $binding['queue'];
            $exchange = $binding['exchange'];
            $this->bindQueue($queue, $exchange, $binding);
        }
    }

    protected function registerHandlers($handlers) {

        foreach ($handlers as $handler) {
            $queue = $handler['queue'];
            $class = $handler['handler'];
            $this->registerHandler($queue, $class, $handlers);
        }
    }

    private function makeRabbitMessage(AMQPMessage $msg) {
        $data = json_decode($msg->body, true);
        return new RabbitMessage($this->channel, $msg, $data);
    }

    /** @var RabbitPublisher  */
    public $publisher;
    /** @var RabbitConsumer  */
    public $consumer;
    /** @var AMQPChannel */
    protected $channel;

    protected $exchangeOptions;
    protected $queueOptions;
    protected $bindOptions;

    public function __construct(AMQPChannel $channel, $exchanges = [], $queues = [], $bindings = [], $handlers = [], $options = []) {
        $this->channel = $channel;

        $this->publisher = new RabbitPublisher($channel, array_get($options, 'basic_publish', []));
        $this->consumer = new RabbitConsumer($channel, array_get($options, 'basic_consume', []));

        $this->exchangeOptions = array_merge(RabbitMQOptions::DEFAULT_EXCHANGE, array_get($options, 'exchange_declare', []));
        $this->queueOptions = array_merge(RabbitMQOptions::DEFAULT_QUEUE, array_get($options, 'queue_declare', []));
        $this->bindOptions = array_merge(RabbitMQOptions::DEFAULT_BIND, array_get($options, 'queue_bind', []));

        $this->declareExchanges($exchanges);
        $this->declareQueues($queues);
        $this->bindQueues($bindings);
        $this->registerHandlers($handlers);
    }

}
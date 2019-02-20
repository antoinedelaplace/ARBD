<?php

namespace T7FT\Services;

use Silex\Application;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitManager
{
    private $app;
    private $connection;
    private $channel;

    function __construct(Application $app, $connection)
    {
        $this->app = $app;
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
        $this->channel->basic_qos(null, 100, true);
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function prepareForConsumer(
        $exchangeName,
        $queueName,
        $routingKey,
        $exchangeError,
        $queueError,
        $routingError
    ) {
        $this->createDirectExchange($exchangeName);
        $this->createQueueAndBindIt($queueName, $exchangeName, $routingKey);

        $this->createDirectExchange($exchangeError);
        $this->createQueueAndBindIt($queueError, $exchangeError, $routingError);
    }

    public function infiniteConsume(
        $queueName,
        $callback
    ) {
        $this->channel->basic_consume(
            $queueName,
            $_SERVER["HOSTNAME"],
            false,
            false,
            false,
            false,
            $callback
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->closeConnection();
    }

    public function pushToQueueErrorForConsumer($msg, $exchangeName, $routingKey)
    {
        $this->publishMessage($msg->body, $exchangeName, $routingKey);
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    public function pushToQueue(
        $exchangeName,
        $queueName,
        $routingKey,
        $data
    ) {
        $this->createDirectExchange($exchangeName);
        $this->createQueueAndBindIt($queueName, $exchangeName, $routingKey);

        $this->publishMessage(json_encode($data), $exchangeName, $routingKey);
    }

    public function createDirectExchange($name, $type = 'direct')
    {
        $this->channel->exchange_declare($name, $type, false, true, false);
    }

    public function createQueue($name)
    {
        // creation d'une queue basic durable
        $this->channel->queue_declare($name, false, true, false, false);
    }

    public function createQueueAndBindIt($name, $exchangeName = null, $routingKey = '')
    {
        $this->createQueue($name);
        if (null === $exchangeName) {
            return ;
        }
        // bind de la queue avec l'exchange et une routing key
        $this->channel->queue_bind($name, $exchangeName, $routingKey);
    }

    public function publishMessage($msg, $exchangeName, $routingKey)
    {
        // creation d'un message persistant
        $msg = new AMQPMessage($msg, ['delivery_mode' => 2]);
        // envoie du message avec l'exchange et la routing key
        $this->channel->basic_publish($msg, $exchangeName, $routingKey);
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}

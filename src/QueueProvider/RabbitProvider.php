<?php

declare(strict_types=1);

namespace App\QueueProvider;

use App\Contracts\QueueProviderInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitProvider implements QueueProviderInterface
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private string $channelName;

    /**
     * RabbitProvider constructor.
     */
    public function __construct()
    {
        /**
         * Create a connection to RabbitAMQP
         */
        $this->connection = new AMQPStreamConnection(
            getenv('RABBITMQ_HOST'),
            getenv('RABBITMQ_PORT'),
            getenv('RABBITMQ_DEFAULT_USER'),
            getenv('RABBITMQ_DEFAULT_PASS')
        );

        $this->channel = $this->connection->channel();

        $this->channelName = getenv('RABBITMQ_QUEUE_NAME');
        $this->channel->queue_declare(
            $this->channelName,    #queue name - Queue names may be up to 255 bytes of UTF-8 characters
            false,          #passive - can use this to check whether an exchange exists without modifying the server state
            false,          #durable - make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
            false,          #exclusive - used by only one connection and the queue will be deleted when that connection closes
            false           #autodelete - queue is deleted when last consumer unsubscribes
        );

        register_shutdown_function(
            static function (AMQPChannel $channel, AMQPStreamConnection $connection) {
                $channel->close();
                $connection->close();
            },
            $this->channel,
            $this->connection
        );
    }

    /**
     * @throws \Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function dispatch(string $message): void
    {
        $this->channel->basic_publish(
            new AMQPMessage($message),
            '',
            $this->channelName
        );
    }

    /**
     * @inheritDoc
     */
    public function consume(string $className): void
    {
        $this->channel->basic_consume(
            $this->channelName, #queue
            '', #consumer tag - Identifier for the consumer, valid within the current channel. just string
            false, #no local - TRUE: the server will not send messages to the connection that published them
            false, #no ack - send a proper acknowledgment from the worker, once we're done with a task
            false, #exclusive - queues may only be accessed by the current connection
            false, #no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
            new $className(), #callback - method that will receive the message
        );

        while ($this->channel->is_consuming()) {
            try {
                $this->channel->wait();
            } catch (\ErrorException $e) {
                // todo: log errors
            }
        }
    }
}

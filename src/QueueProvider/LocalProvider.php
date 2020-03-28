<?php

declare(strict_types=1);

namespace App\QueueProvider;

use App\Contracts\QueueProviderInterface;
use App\Handler\MessageHandler;
use PhpAmqpLib\Message\AMQPMessage;

class LocalProvider implements QueueProviderInterface
{
    public function dispatch(string $message): void
    {
        (new MessageHandler())(new AMQPMessage($message));
    }

    /**
     * @inheritDoc
     */
    public function consume(string $className): void
    {
        throw new \RuntimeException('can`t use this method in local context');
    }
}

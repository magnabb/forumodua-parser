<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Message;
use App\Repository\MessageRepository;
use PhpAmqpLib\Message\AMQPMessage;

class MessageHandler
{
    public function __invoke($message): void
    {
        var_dump(__FILE__, 'invoked'); // left it for knowing it`s work

        (new MessageRepository())->save(Message::fromJson($message->body));
    }
}

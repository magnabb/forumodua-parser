<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Message;
use App\Repository\MessageRepository;
use PhpAmqpLib\Message\AMQPMessage;

class MessageHandler
{
    public function __invoke(AMQPMessage $message): void
    {
        (new MessageRepository())->save(Message::fromJson($message->body));
    }
}

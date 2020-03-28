<?php

declare(strict_types=1);

namespace App;

use App\Contracts\ConsoleApplicationInterface;
use App\Handler\MessageHandler;
use App\QueueProvider\RabbitProvider;

class Consumer implements ConsoleApplicationInterface
{
    public function run(array $options = []): void
    {
        $provider = new RabbitProvider();
        $provider->consume(MessageHandler::class);
    }
}

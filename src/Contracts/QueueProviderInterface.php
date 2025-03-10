<?php

declare(strict_types=1);

namespace App\Contracts;

interface QueueProviderInterface
{
    public function dispatch(string $message): void;

    /**
     * @param string $className
     */
    public function consume(string $className): void;
}

<?php

declare(strict_types=1);

namespace App\Contracts;

interface ConsoleApplicationInterface
{
    public function run(array $options = []): void;
}

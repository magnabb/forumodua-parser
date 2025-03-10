<?php

declare(strict_types=1);

namespace App\Contracts;

interface ParserInterface
{
    public static function getType(): string;

    public function parse(string $parseUrl, int $maxPosts): void;
}

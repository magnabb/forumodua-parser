<?php

declare(strict_types=1);

namespace App\Parser;

use App\Contracts\ParserInterface;
use App\Parser\Parsers\ForumoduaParser;

class ParsersStrategy
{
    private array $strategies = [];

    public function __construct()
    {
        $this->strategies[ForumoduaParser::getType()] = new ForumoduaParser();
    }

    public function get(string $type): ParserInterface
    {
        if (!array_key_exists($type, $this->strategies)) {
            throw new \InvalidArgumentException("Type $type does not exists");
        }

        return $this->strategies[$type];
    }
}

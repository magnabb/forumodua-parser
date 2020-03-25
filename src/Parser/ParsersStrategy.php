<?php declare(strict_types=1);

namespace App\Parser;

use App\Contracts\ParserInterface;

class ParsersStrategy
{
    private array $strategies = [];

    public function __construct()
    {
        // todo: register strategies
//        $this->strategies[ConcreteParser::getType()] = new ConcreteParser();
    }

    public function get(string $type): ParserInterface
    {
        if (!array_key_exists($type, $this->strategies)) {
            throw new \InvalidArgumentException("Type $type does not exists");
        }

        return $this->strategies[$type];
    }
}

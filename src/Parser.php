<?php

declare(strict_types=1);

namespace App;

use App\Contracts\ConsoleApplicationInterface;
use App\Parser\Parsers\ForumoduaParser;
use App\Parser\ParsersStrategy;

class Parser implements ConsoleApplicationInterface
{
    public function run(array $options = []): void
    {
        $defaultOptions = [
            'parse' => '',
            'max' => 10,
        ];

        $options = array_merge($defaultOptions, $options);

        if (!filter_var($options['parse'], FILTER_VALIDATE_URL)) {
            throw new \RuntimeException('Invalid url');
        }

        if (!filter_var((int) $options['max'], FILTER_VALIDATE_INT)) {
            throw new \RuntimeException('Invalid max value');
        }

        $parser = (new ParsersStrategy())->get(ForumoduaParser::TYPE_NAME);
        $parser->parse($options['parse'], $options['max']);
    }
}

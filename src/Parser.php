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
            CLI_ARGUMENT_NAME_PARSE => '',
            CLI_ARGUMENT_NAME_MAX => 10,
        ];

        $options = array_merge($defaultOptions, $options);

        if (!filter_var($options[CLI_ARGUMENT_NAME_PARSE], FILTER_VALIDATE_URL)) {
            throw new \RuntimeException('Invalid url');
        }

        if (!filter_var((int) $options[CLI_ARGUMENT_NAME_MAX], FILTER_VALIDATE_INT)) {
            throw new \RuntimeException('Invalid max value');
        }

        $parser = (new ParsersStrategy())->get(ForumoduaParser::TYPE_NAME);
        $parser->parse($options[CLI_ARGUMENT_NAME_PARSE], (int) $options[CLI_ARGUMENT_NAME_MAX]);
    }
}

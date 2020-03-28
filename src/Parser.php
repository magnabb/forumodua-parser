<?php

declare(strict_types=1);

namespace App;

use App\Contracts\ConsoleApplicationInterface;
use App\Parser\Parsers\ForumoduaParser;
use App\Parser\ParsersStrategy;

class Parser implements ConsoleApplicationInterface
{
    public function run(): void
    {
        $parser = (new ParsersStrategy())->get(ForumoduaParser::TYPE_NAME);

        $parser->parse('https://forumodua.com/showthread.php?t=252286');// todo: get from argument
    }
}

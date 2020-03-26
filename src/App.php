<?php

declare(strict_types=1);

namespace App;

use App\Parser\Parsers\ForumoduaParser;
use App\Parser\ParsersStrategy;

class App
{
    public function run()
    {
        $parser = (new ParsersStrategy())->get(ForumoduaParser::FORUMODUA);

        $parser->parse('https://forumodua.com/showthread.php?t=252286');// todo: get from argument
    }
}

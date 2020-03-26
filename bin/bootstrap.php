<?php

use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/../vendor/autoload.php';

// console applications can work long term
set_time_limit(0);

(new Dotenv())->overload(dirname(__DIR__) . '/.env');

<?php declare(strict_types=1);

namespace App;

class Consumer
{
    public function run(): void
    {
        while(true) {
            echo 'PHP OK' . PHP_EOL;
            sleep(30); // todo tmp
        }
    }
}

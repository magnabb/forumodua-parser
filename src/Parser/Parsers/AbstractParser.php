<?php

declare(strict_types=1);

namespace App\Parser\Parsers;

use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractParser
{
    protected string $password;
    protected string $login;
    protected string $loginPath;

    public function parse(string $parseUrl): void
    {
        $crawler = $this->auth();
        
        if (!$this->checkAuth($crawler)) {
            trigger_error('auth failed', E_USER_WARNING);
        }

        $this->parseContent($parseUrl);
    }

    abstract protected function auth(): Crawler;

    abstract protected function checkAuth(Crawler $crawler): bool;

    abstract protected function parseContent(string $parseUrl);
}

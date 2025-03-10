<?php

declare(strict_types=1);

namespace App\Parser\Parsers;

use App\Contracts\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractParser implements ParserInterface
{
    protected string $password;
    protected string $login;
    protected string $loginPath;

    public function parse(string $parseUrl, int $maxPosts): void
    {
        $crawler = $this->auth();
        
        if (!$this->checkAuth($crawler)) {
            trigger_error('auth failed', E_USER_WARNING);
        }

        $this->parseContent($parseUrl, $maxPosts);
    }

    abstract protected function auth(): Crawler;

    abstract protected function checkAuth(Crawler $crawler): bool;

    abstract protected function parseContent(string $parseUrl, int $maxPosts): void;
}

<?php

declare(strict_types=1);

namespace App\Parser\Parsers;

use App\Contracts\ParserInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ForumoduaParser extends AbstractParser implements ParserInterface
{
    public const FORUMODUA = 'forumodua';

    private HttpBrowser $browser;

    public function __construct()
    {
        $this->loginPath = getenv('FORUMODUA_LOGIN_PATH');
        $this->login = getenv('FORUMODUA_LOGIN');
        $this->password = getenv('FORUMODUA_PASSWORD');
        $this->browser = new HttpBrowser(HttpClient::create());
    }

    public static function getType(): string
    {
        return self::FORUMODUA;
    }

    protected function auth(): Crawler
    {
        $crawler = $this->browser->request('GET', $this->loginPath);

        $form = $crawler->selectButton('Вход')->form();
        $form['vb_login_username'] = $this->login;
        $form['vb_login_password'] = $this->password;

        return $this->browser->submit($form);
    }

    protected function checkAuth(Crawler $crawler): bool
    {
        try {
            return (bool)$crawler->filter('p.restore')->text();
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function parseContent(string $parseUrl)
    {
        $crawler = $this->browser->request('GET', $parseUrl);

        $posts = $crawler->filter('.postcontainer');
        $posts
            ->reduce(function (Crawler $post, int $idx) {
                return $this->isPostCorrect($post, $idx);
            })
            ->each(static function (Crawler $post) {
                echo $post->filter('.date')->text() . PHP_EOL;
                // todo: dispatch save message
            });
    }

    private function isPostCorrect(Crawler $post, int $idx, int $maxPosts = 10): bool
    {
        if ($idx > $maxPosts) {
            return false;
        }

        try {
            $post->filter('.date')->text();// todo
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Parser\Parsers;

use App\Contracts\QueueProviderInterface;
use App\Entity\Message;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ForumoduaParser extends AbstractParser
{
    public const TYPE_NAME = 'forumodua';

    private HttpBrowser $browser;
    private QueueProviderInterface $queueProvider;

    public function __construct(QueueProviderInterface $queueProvider)
    {
        $this->loginPath = getenv('FORUMODUA_LOGIN_PATH');
        $this->login = getenv('FORUMODUA_LOGIN');
        $this->password = getenv('FORUMODUA_PASSWORD');
        $this->browser = new HttpBrowser(HttpClient::create());
        $this->queueProvider = $queueProvider;
    }

    public static function getType(): string
    {
        return self::TYPE_NAME;
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

    protected function parseContent(string $parseUrl): void
    {
        $crawler = $this->browser->request('GET', $parseUrl);

        $posts = $crawler->filter('.postcontainer');
        $subject = $crawler->filter('span.threadtitle a')->text();
        $posts
            ->reduce(function (Crawler $post, int $idx) {
                return $this->isPostCorrect($post, $idx);
            })
            ->each(function (Crawler $post) use ($subject) {
                $message = new Message(
                    $subject,
                    $post->filter('.username')->text(),
                    \DateTimeImmutable::createFromFormat('d?m?Y*H?i', $post->filter('.date')->text()),
                    strip_tags($post->filter('.postcontent')->text())
                );

                $this->queueProvider->dispatch(json_encode($message, JSON_THROW_ON_ERROR, 512));
            });
    }

    private function isPostCorrect(Crawler $post, int $idx, int $maxPosts = 1): bool
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

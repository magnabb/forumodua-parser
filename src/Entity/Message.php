<?php

declare(strict_types=1);

namespace App\Message;

use App\Contracts\EntityInterface;

class Message implements EntityInterface
{
    private string $subject;

    private string $author;

    private \DateTimeImmutable $date;

    private string $text;

    public function __construct(string $subject, string $author, \DateTimeImmutable $date, string $text)
    {
        $this->subject = $subject;
        $this->author = $author;
        $this->date = $date;
        $this->text = $text;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getText(): string
    {
        return $this->text;
    }
}

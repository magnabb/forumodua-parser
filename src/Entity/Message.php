<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\EntityInterface;

class Message implements EntityInterface, \JsonSerializable
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

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            $this->subject,
            $this->author,
            $this->date->format('U'),
            $this->text
        ];
    }

    public static function fromJson(string $jsonString): self
    {
        $data = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);

        // denormalize datetime
        $data[2] = \DateTimeImmutable::createFromFormat('U', $data[2]);

        return new self(...$data);
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

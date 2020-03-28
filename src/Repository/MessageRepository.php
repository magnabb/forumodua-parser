<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contracts\EntityInterface;
use App\Contracts\RepositoryInterface;
use App\Entity\Message;

class MessageRepository implements RepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $host = getenv('POSTGRES_LISTEN_ADDRESS');
        $db   = getenv('POSTGRES_DB');
        $user = getenv('POSTGRES_USER');
        $pass = getenv('POSTGRES_PASSWORD');

        $dsn = sprintf('pgsql:host=%s;dbname=%s', $host, $db,);

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new \PDO($dsn, $user, $pass, $options);
    }

    /**
     * @param Message|EntityInterface $entity
     * @return int
     */
    public function save(EntityInterface $entity): int
    {
        $stmt = $this->pdo->prepare('CALL insert_post(:topic, :author, :date, :text);');

        $stmt->bindValue(':topic', $entity->getSubject());
        $stmt->bindValue(':author', $entity->getAuthor());
        $stmt->bindValue(':date', $entity->getDate()->format('d.m.Y H:i'));
        $stmt->bindValue(':text', $entity->getText());

        $stmt->execute();

        return (int) $this->pdo->lastInsertId('post_id_seq');
    }
}

<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contracts\RepositoryInterface;
use App\Message\EntityInterface;
use App\Message\Message;

class MessageRepository implements RepositoryInterface
{
    /**
     * @param Message|EntityInterface $entity
     * @return bool
     */
    public function save(EntityInterface $entity): bool
    {
        return false; // TODO: Implement save() method.
    }
}

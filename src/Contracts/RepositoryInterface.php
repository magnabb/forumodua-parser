<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Message\EntityInterface;

interface RepositoryInterface
{
    public function save(EntityInterface $entity): bool;
}

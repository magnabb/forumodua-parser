<?php

declare(strict_types=1);

namespace App\Contracts;

interface RepositoryInterface
{
    public function save(EntityInterface $entity): int;
}

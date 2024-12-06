<?php

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\AggregateRoot;

/**
 * Interface for a database repository that provides basic transaction management
 * and batch processing capabilities.
 */
interface DatabaseRepository
{
    public function save(AggregateRoot $object): void;

    public function persist(AggregateRoot $object): void;

    public function apply(): void;
}

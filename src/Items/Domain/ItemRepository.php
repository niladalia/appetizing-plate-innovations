<?php

namespace App\Items\Domain;

use App\Items\Domain\ValueObject\ItemId;
use App\Shared\Domain\Repository\DatabaseRepository;

/**
 * @implements DatabaseRepository<Item>
 */
interface ItemRepository extends DatabaseRepository
{
    public function findById(ItemId $id): ?Item;
}

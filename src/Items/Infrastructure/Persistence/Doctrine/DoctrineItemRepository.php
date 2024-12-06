<?php

namespace App\Items\Infrastructure\Persistence\Doctrine;

use App\Items\Domain\Item;
use App\Items\Domain\ItemRepository;
use App\Items\Domain\ValueObject\ItemId;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineDatabaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineItemRepository extends DoctrineDatabaseRepository implements ItemRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findById(ItemId $id): ?Item
    {
        return $this->getEntityManager()->find(Item::class, $id);
    }
}

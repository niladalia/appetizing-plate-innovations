<?php

namespace App\Orders\Infrastructure\Persistence\Doctrine;

use App\Orders\Domain\Order;
use App\Orders\Domain\OrderItem;
use App\Orders\Domain\OrderItemRepository;
use App\Orders\Domain\OrderRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineDatabaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrderItemRepository extends DoctrineDatabaseRepository implements OrderItemRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }
}

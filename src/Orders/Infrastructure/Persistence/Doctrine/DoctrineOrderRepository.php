<?php

namespace App\Orders\Infrastructure\Persistence\Doctrine;

use App\Orders\Domain\Order;
use App\Orders\Domain\OrderRepository;
use App\Orders\Domain\ValueObject\OrderId;
use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineDatabaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrderRepository extends DoctrineDatabaseRepository implements OrderRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findById(OrderId $id): ?Order
    {
        return $this->getEntityManager()->find(Order::class, $id);
    }
}

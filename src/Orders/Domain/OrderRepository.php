<?php

namespace App\Orders\Domain;

use App\Orders\Domain\Order;
use App\Orders\Domain\ValueObject\OrderId;
use App\Shared\Domain\Repository\DatabaseRepository;

interface OrderRepository extends DatabaseRepository
{
    public function findById(OrderId $id): ?Order;
}

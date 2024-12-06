<?php

namespace App\Orders\Domain\Services;

use App\Orders\Domain\Exceptions\OrderNotFound;
use App\Orders\Domain\Order;
use App\Orders\Domain\OrderRepository;
use App\Orders\Domain\ValueObject\OrderId;

class OrderFinder
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }


    public function __invoke(OrderId $id): Order
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            OrderNotFound::throw($id->getValue());
        }

        return $order;
    }
}

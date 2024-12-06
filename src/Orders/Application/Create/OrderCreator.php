<?php

namespace App\Orders\Application\Create;

use App\Orders\Application\Create\DTO\CreateOrderRequest;
use App\Orders\Domain\Order;
use App\Orders\Domain\OrderRepository;
use App\Orders\Domain\ValueObject\OrderId;

class OrderCreator
{
    public function __construct(private OrderRepository $repository)
    {
    }

    public function __invoke(CreateOrderRequest $createOrderRequest): void
    {
       $item = Order::create(
           new OrderId($createOrderRequest->id())
       );

       $this->repository->save($item);
    }
}

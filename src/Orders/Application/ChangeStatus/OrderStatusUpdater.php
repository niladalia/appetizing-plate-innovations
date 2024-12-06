<?php

namespace App\Orders\Application\ChangeStatus;

use App\Orders\Application\ChangeStatus\DTO\ChangeStatusRequest;

use App\Orders\Domain\OrderRepository;
use App\Orders\Domain\Services\OrderFinder;
use App\Orders\Domain\ValueObject\OrderId;
use App\Orders\Domain\ValueObject\OrderStatus;

class OrderStatusUpdater
{
    public function __construct(private OrderRepository $repository, private OrderFinder $finder)
    {
    }

    public function __invoke(ChangeStatusRequest $changeStatusRequest): void
    {
       $order = $this->finder->__invoke(new OrderId($changeStatusRequest->id()));

       $order->changeStatus(
           OrderStatus::fromString($changeStatusRequest->status())
       );

       $this->repository->save($order);
    }
}

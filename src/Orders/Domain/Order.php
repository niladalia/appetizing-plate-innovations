<?php

namespace App\Orders\Domain;

use App\Orders\Domain\ValueObject\OrderId;
use App\Orders\Domain\ValueObject\OrderStatus;
use App\Orders\Domain\ValueObject\OrderTotalPrice;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\Exceptions\InvalidArgument;

class Order extends AggregateRoot
{
    private $items = [];

    public function __construct(
        private OrderId $id,
        private OrderStatus $status
    )
    {
    }

    public static function create(
        OrderId $id
    ): self
    {
        $status = OrderStatus::RECEIVED;
        return new self($id, $status);
    }

    public function changeStatus(OrderStatus $newStatus): void
    {
        $this->status->assertCanChangeTo($newStatus);

        $this->status = $newStatus;
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    /**
     * @return OrderItem[]
     *
     * Note: Preferably, this method should return an OrderItems object
     * instead of a raw array for better encapsulation.
     */
    public function items(): array
    {
        return $this->items->toArray();
    }
    /*
    * Note: Preferably, this method should return an OrderItems object
    * instead of a raw array for better encapsulation.
    */
    public function totalPrice(): OrderTotalPrice
    {
        $totalPrice = 0;

        foreach ($this->items() as $item) {
            $totalPrice += $item->subtotal()->getValue();
        }

        return new OrderTotalPrice($totalPrice);
    }

    /**
     * This could be also be placed inside the OrderStatus ValueObject, but i think is more clear
     *
     */
    private function canChangeTo(OrderStatus $newStatus): bool
    {
        return match ($this->status) {
            OrderStatus::RECEIVED => $newStatus === OrderStatus::IN_PREPARATION,
            OrderStatus::IN_PREPARATION => $newStatus === OrderStatus::READY,
            OrderStatus::READY => false,
        };
    }
}

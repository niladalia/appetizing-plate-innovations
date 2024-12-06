<?php

namespace App\Orders\Domain;

use App\Items\Domain\Item;
use App\Items\Domain\ValueObject\ItemPrice;
use App\Orders\Domain\ValueObject\OrderItemId;
use App\Orders\Domain\ValueObject\OrderItemQuantity;
use App\Orders\Domain\ValueObject\OrderItemSubtotal;
use App\Shared\Domain\AggregateRoot;

class OrderItem extends AggregateRoot
{
    public function __construct(
        private OrderItemId $id,
        private Order $order,
        private Item $item,
        private OrderItemQuantity $quantity,
        private OrderItemSubtotal $subtotal
    )
    {
    }

    public static function create(
        OrderItemId $id,
        Order $order,
        Item $item,
        OrderItemQuantity $quantity
    ): self
    {
        $subtotal = OrderItemSubtotal::calculate($item->price(), $quantity);

        return new self($id, $order, $item, $quantity, $subtotal);
    }

    public function id(): OrderItemId
    {
        return $this->id;
    }

    public function quantity(): OrderItemQuantity
    {
        return $this->quantity;
    }

    public function subtotal(): OrderItemSubtotal
    {
        return $this->subtotal;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getId(): string
    {
        return $this->getOrder()->id()->getValue() . '-' . $this->getItem()->id()->getValue();
    }
}

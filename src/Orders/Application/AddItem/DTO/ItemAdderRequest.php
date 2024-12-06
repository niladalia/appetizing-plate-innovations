<?php

namespace App\Orders\Application\AddItem\DTO;

class ItemAdderRequest
{
    public function __construct(
        private string $id,
        private string $orderId,
        private string $itemId,
        private int $quantity
    )
    {
    }

    public function id(): string
    {
        return $this->id;
    }
    public function orderId(): string
    {
        return $this->orderId;
    }

    public function itemId(): string
    {
        return $this->itemId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}

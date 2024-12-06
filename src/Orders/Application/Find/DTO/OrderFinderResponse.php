<?php

namespace App\Orders\Application\Find\DTO;

class OrderFinderResponse
{
    public function __construct(
        private string $id,
        private string $status,
        private float $totalPrice
    )
    {
    }

    public function id(): string{
        return $this->id;
    }
    public function totalPrice(): float
    {
        return $this->totalPrice;
    }

    public function status(): string
    {
        return $this->status;
    }
}

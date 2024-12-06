<?php

namespace App\Orders\Application\Create\DTO;

class CreateOrderRequest
{
    public function __construct(
        private string $id
    )
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}

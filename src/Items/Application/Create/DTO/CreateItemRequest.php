<?php

namespace App\Items\Application\Create\DTO;

class CreateItemRequest
{
    public function __construct(
        private string $id,
        private string $name,
        private string $description,
        private float $price
    )
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function price(): float
    {
        return $this->price;
    }
}

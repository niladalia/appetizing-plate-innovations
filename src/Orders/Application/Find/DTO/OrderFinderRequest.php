<?php

namespace App\Orders\Application\Find\DTO;

class OrderFinderRequest
{
    public function __construct(private string $id)
    {
    }

    public function id(): string{
        return $this->id;
    }
}

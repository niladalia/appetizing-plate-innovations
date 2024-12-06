<?php

namespace App\Orders\Application\ChangeStatus\DTO;

class ChangeStatusRequest
{
    public function __construct(
        private string $id,
        private string $status
    )
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function status(): string
    {
        return $this->status;
    }
}

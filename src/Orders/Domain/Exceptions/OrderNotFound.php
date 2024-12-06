<?php

namespace App\Orders\Domain\Exceptions;

use DomainException;

class OrderNotFound extends DomainException
{
    public static function throw(?string $id = '')
    {
        throw new self("Order {$id} not found");
    }
    public function getStatusCode()
    {
        return 400;
    }
}

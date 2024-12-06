<?php

namespace App\Items\Domain\Exceptions;

use DomainException;

class ItemNotFound extends DomainException
{
    public static function throw(?string $id = '')
    {
        throw new self("Item {$id} not found");
    }
    public function getStatusCode()
    {
        return 400;
    }
}

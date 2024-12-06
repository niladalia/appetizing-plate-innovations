<?php

namespace App\Items\Domain\ValueObject;

use App\Shared\Domain\Exceptions\InvalidArgument;
use App\Shared\Domain\ValueObject\FloatValueObject;

final class ItemPrice extends FloatValueObject
{
    public function __construct(float $value = null)
    {
        parent::__construct($value);
        $this->validate();
    }

    protected function validate()
    {
        if ($this->value > 99 || $this->value < 0) {
            InvalidArgument::throw('El precio tiene que estar entre 0 y 99');
        }
    }
}

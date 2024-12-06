<?php

namespace App\Shared\Domain\ValueObject;

abstract class FloatValueObject
{
    public function __construct(protected ?float $value = null) {}

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function toInt(): int
    {
        return intval($this->value);
    }
}

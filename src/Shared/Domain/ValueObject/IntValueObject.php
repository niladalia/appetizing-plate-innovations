<?php

namespace App\Shared\Domain\ValueObject;

abstract class IntValueObject
{
    public function __construct(protected ?int $value = null) {}

    public function getValue(): ?int
    {
        return $this->value;
    }
}

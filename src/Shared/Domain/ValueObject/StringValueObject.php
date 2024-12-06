<?php

namespace App\Shared\Domain\ValueObject;

abstract class StringValueObject
{
    public function __construct(protected ?string $value = null) {}

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function __tostring(): string
    {
        return $this->getValue() ?? '';
    }
}

<?php

namespace App\Orders\Domain\ValueObject;

use App\Shared\Domain\Exceptions\InvalidArgument;

enum OrderStatus: string
{
    case RECEIVED = 'received';
    case IN_PREPARATION = 'in_preparation';
    case READY = 'ready_to_serve';

    public static function fromString(string $value): self
    {
        self::validate($value);

        return self::from($value);
    }


    public function getValue(): string
    {
        return $this->value;
    }

    // Throws an exception if the transition is not allowed
    public function assertCanChangeTo(OrderStatus $newStatus): void
    {
        if (!$this->canChangeTo($newStatus)) {
            throw new InvalidArgument(
                sprintf('Cannot change status from %s to %s', $this->getValue(), $newStatus->getValue())
            );
        }
    }

    private static function validate(string $value): void
    {
        foreach (self::cases() as $status) {
            if ($status->value === $value) {
                return; // If valid, just return
            }
        }

        throw new InvalidArgument(sprintf('Invalid order status: "%s".', $value));
    }
    private function canChangeTo(OrderStatus $newStatus): bool
    {
        return match ($this) {
            OrderStatus::RECEIVED => $newStatus === OrderStatus::IN_PREPARATION,
            OrderStatus::IN_PREPARATION => $newStatus === OrderStatus::READY,
            OrderStatus::READY => false, // No more state changes after "READY"
        };
    }

}

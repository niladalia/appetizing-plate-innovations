<?php

namespace App\Orders\Domain\ValueObject;

use App\Items\Domain\ValueObject\ItemPrice;
use App\Shared\Domain\ValueObject\FloatValueObject;

class OrderItemSubtotal extends FloatValueObject
{
    public static function calculate(ItemPrice $price, OrderItemQuantity $quantity): self {
        $totalPrice = $price->getValue() * $quantity->getValue();
        return new self($totalPrice);
    }
}

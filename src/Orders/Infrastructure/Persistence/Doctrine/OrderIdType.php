<?php

namespace App\Orders\Infrastructure\Persistence\Doctrine;

use App\Orders\Domain\ValueObject\OrderId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class OrderIdType extends GuidType
{
    public function getName(): string
    {
        return 'item_id';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?OrderId
    {
        return new OrderId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value->getValue();
    }
}

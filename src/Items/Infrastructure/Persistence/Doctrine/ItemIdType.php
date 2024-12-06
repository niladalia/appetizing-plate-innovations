<?php

namespace App\Items\Infrastructure\Persistence\Doctrine;

use App\Items\Domain\ValueObject\ItemId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class ItemIdType extends GuidType
{
    public function getName(): string
    {
        return 'item_id';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ItemId
    {
        return new ItemId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value->getValue();
    }
}

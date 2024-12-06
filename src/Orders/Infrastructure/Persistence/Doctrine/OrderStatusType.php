<?php

namespace App\Orders\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\StringType;
use App\Orders\Domain\ValueObject\OrderStatus;

class OrderStatusType extends StringType
{
    const ORDER_STATUS = 'order_status'; // Custom type name

    public function convertToPHPValue($value, AbstractPlatform $platform): ?OrderStatus
    {
        // Convert the database value to the enum value
        return OrderStatus::from($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        // Convert the enum value to its database value (string)
        return $value->value;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        // Specify the SQL declaration for the ENUM field
        return "ENUM('received', 'in_preparation', 'ready_to_serve') DEFAULT 'received' ";
    }

    public function convertToPHPValueSQL($sqlExpr, AbstractPlatform $platform): string
    {
        return $sqlExpr;
    }

    public function getName()
    {
        return self::ORDER_STATUS;
    }
}

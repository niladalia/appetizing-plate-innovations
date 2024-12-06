<?php

namespace App\Shared\Utils;

use ReflectionClass;

class ClassUtils
{
    public static function getAttributeFromClass(string $className, string $attributeClassName): mixed
    {
        $reflectionClass = new ReflectionClass($className);
        $attributes = $reflectionClass->getAttributes($attributeClassName);
        if (empty($attributes)) {
            $parent = $reflectionClass->getParentClass();
            if ($parent !== false) {
                return static::getAttributeFromClass($parent->getName(), $attributeClassName);
            }
            return null;
        }

        $firstAttribute = reset($attributes);
        return $firstAttribute->newInstance();
    }

    public static function usesTrait(string $className, string $traitName): bool
    {
        return in_array($traitName, class_uses($className));
    }
}
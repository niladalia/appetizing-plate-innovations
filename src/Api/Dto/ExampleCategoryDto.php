<?php

namespace App\Api\Dto;

use Symfony\Component\Validator\Constraints as Assert;

use App\Api\Entity\ExampleCategory;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ORM\OptimisticLockException;

class ExampleCategoryDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 5, max: 500)]
        #[Groups(["create","update"])]
        public readonly string $name,

        #[Assert\Positive]
        #[Groups(["update"])]
        public readonly ?int $version,
    ) {
    }

    public function update(?ExampleCategory $item = null): ExampleCategory
    {
        if (!$item) {
            $item = new ExampleCategory();
        }
        if ($this->version !== null) {
            if ($item->getVersion() === null) {
                throw OptimisticLockException::notVersioned($item::class);
            }
            if($this->version !== $item->getVersion()) {
                throw OptimisticLockException::lockFailedVersionMismatch($item, $this->version, $item->getVersion());
            }
        }
        return $item
            ->setName($this->name);
    }
}
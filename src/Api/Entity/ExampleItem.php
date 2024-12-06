<?php

namespace App\Api\Entity;

use App\Api\Repository\ExampleItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Shared\Doctrine\Types\BinaryUuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ExampleItemRepository::class)]
class ExampleItem
{
    #[ORM\Id]
    #[ORM\Column(type: BinaryUuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['example_item:read'])]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 180)]
    #[Groups(['example_item:read'])]
    private string $name = '';

    #[ORM\ManyToOne(targetEntity: ExampleCategory::class, inversedBy: 'exampleItems', fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(name: 'example_category_uuid', referencedColumnName: 'uuid', onDelete: 'SET NULL')]
    #[Groups(['example_item:example_category:read'])]
    private ?ExampleCategory $exampleCategory = null;

    #[ORM\Column(type: 'integer', nullable: false, updatable: false, options: ['default' => 1])]
    #[ORM\Version]
    #[Groups(['version:read'])]
    private ?int $version = null;

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function getExampleCategory(): ?ExampleCategory
    {
        return $this->exampleCategory;
    }

    public function setExampleCategory(?ExampleCategory $exampleCategory): static
    {
        $this->exampleCategory = $exampleCategory;
        return $this;
    }
}

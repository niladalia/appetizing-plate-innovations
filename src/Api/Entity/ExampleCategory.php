<?php

namespace App\Api\Entity;

use App\Api\Repository\ExampleCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Shared\Doctrine\Types\BinaryUuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ExampleCategoryRepository::class)]
class ExampleCategory
{
    #[ORM\Id]
    #[ORM\Column(type: BinaryUuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['example_category:read'])]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 180)]
    #[Groups(['example_category:read'])]
    private string $name = '';

    #[ORM\OneToMany(targetEntity: ExampleItem::class, mappedBy: 'exampleCategory', fetch: 'EXTRA_LAZY')]
    #[Groups(['example_category:example_item:read'])]
    private Collection $exampleItems; 

    #[ORM\Column(type: 'integer', nullable: false, updatable: false, options: ['default' => 1])]
    #[ORM\Version]
    #[Groups(['version:read'])]
    private ?int $version = null;

    public function __construct()
    {
        $this->exampleItems = new ArrayCollection();
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;
        return $this;
    }

    #[Groups(['example_category:example_item:count'])]
    public function getExampleItemsCount(): int
    {
        return count($this->getExampleItems());
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

    public function getExampleItems(): Collection
    {
        return $this->exampleItems;
    }
}

<?php

namespace App\Items\Application\Finder;

use App\Items\Domain\Exceptions\ItemNotFound;
use App\Items\Domain\Item;
use App\Items\Domain\ItemRepository;
use App\Items\Domain\ValueObject\ItemId;

class ItemFinder
{
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }


    public function __invoke(ItemId $id): Item
    {
        $item = $this->itemRepository->findById($id);

        if (!$item) {
            ItemNotFound::throw($id->getValue());
        }

        return $item;
    }
}

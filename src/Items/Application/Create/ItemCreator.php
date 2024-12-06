<?php

namespace App\Items\Application\Create;

use App\Items\Application\Create\DTO\CreateItemRequest;
use App\Items\Domain\Item;
use App\Items\Domain\ItemRepository;
use App\Items\Domain\ValueObject\ItemDescription;
use App\Items\Domain\ValueObject\ItemId;
use App\Items\Domain\ValueObject\ItemName;
use App\Items\Domain\ValueObject\ItemPrice;

class ItemCreator
{
    public function __construct(private ItemRepository $repository)
    {
    }

    public function __invoke(CreateItemRequest $createItemRequest): void
    {
       $item = Item::create(
           new ItemId($createItemRequest->id()),
           new ItemName($createItemRequest->name()),
           new ItemDescription($createItemRequest->description()),
           new ItemPrice($createItemRequest->price())
       );

       $this->repository->save($item);
    }
}

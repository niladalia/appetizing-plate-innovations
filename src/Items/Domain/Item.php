<?php

namespace App\Items\Domain;


use App\Items\Domain\ValueObject\ItemDescription;
use App\Items\Domain\ValueObject\ItemId;
use App\Items\Domain\ValueObject\ItemName;
use App\Items\Domain\ValueObject\ItemPrice;
use App\Shared\Domain\AggregateRoot;

class Item extends AggregateRoot
{
    private $orders = [];

    public function __construct(
        private ItemId $id,
        private ItemName $name,
        private ItemDescription $description,
        private ItemPrice $price
    )
    {
    }

    public static function create(
        ItemId $id,
        ItemName $name,
        ItemDescription $description,
        ItemPrice $price
    ){
        return new self($id, $name, $description, $price);
    }
    public function price(): ItemPrice
    {
        return $this->price;
    }

    public function id(): ItemId
    {
        return $this->id;
    }

    public function name(): ItemName
    {
        return $this->name;
    }

    public function description(): ItemDescription
    {
        return $this->description;
    }
}

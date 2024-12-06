<?php

namespace App\Orders\Application\AddItem;

use App\Items\Application\Finder\ItemFinder;
use App\Items\Domain\ValueObject\ItemId;
use App\Orders\Application\AddItem\DTO\ItemAdderRequest;
use App\Orders\Domain\Services\OrderFinder;
use App\Orders\Domain\OrderItem;
use App\Orders\Domain\OrderItemRepository;
use App\Orders\Domain\ValueObject\OrderId;
use App\Orders\Domain\ValueObject\OrderItemId;
use App\Orders\Domain\ValueObject\OrderItemQuantity;

class ItemAdder
{
    public function __construct(
        private OrderItemRepository $orderItemRepository,
        private OrderFinder $orderFinder,
        private ItemFinder $itemFinder
    )
    {
    }

    public function __invoke(ItemAdderRequest $itemAdderRequest): void
    {
        $oder = $this->orderFinder->__invoke(new OrderId($itemAdderRequest->orderId()));
        $item = $this->itemFinder->__invoke(new ItemId($itemAdderRequest->itemId()));

        $orderItem = OrderItem::create(
            new OrderItemId($itemAdderRequest->id()),
            $oder,
            $item,
            new OrderItemQuantity($itemAdderRequest->quantity())
        );

        $this->orderItemRepository->save($orderItem);
    }
}

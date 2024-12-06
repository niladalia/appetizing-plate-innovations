<?php

namespace App\Orders\Application\Find;

use App\Orders\Application\Find\DTO\OrderFinderRequest;
use App\Orders\Application\Find\DTO\OrderFinderResponse;

use App\Orders\Domain\ValueObject\OrderId;
use App\Orders\Domain\Services\OrderFinder as DomainOrderFinder;

class OrderFinder
{
    public function __construct(private DomainOrderFinder $domainFinder)
    {
    }


    public function __invoke(OrderFinderRequest $finderRequest): OrderFinderResponse
    {
        $order = $this->domainFinder->__invoke(new OrderId($finderRequest->id()));

        return new OrderFinderResponse(
          $order->id()->getValue(),
          $order->status()->getValue(),
          $order->totalPrice()->getValue()
        );
    }
}

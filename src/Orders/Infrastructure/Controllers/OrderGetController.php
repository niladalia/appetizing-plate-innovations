<?php

namespace App\Orders\Infrastructure\Controllers;


use App\Orders\Application\AddItem\DTO\ItemAdderRequest;
use App\Orders\Application\AddItem\ItemAdder;
use App\Orders\Application\Find\DTO\OrderFinderRequest;
use App\Orders\Application\Find\DTO\OrderFinderResponse;
use App\Orders\Application\Find\OrderFinder;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class OrderGetController extends ApiController
{
    public function __invoke(Request $request,string $id, OrderFinder $finder): JsonResponse
    {
        $finderRequest = new OrderFinderRequest(
            $id
        );

        /**
         * @var $finderResponse OrderFinderResponse
         **/
        $finderResponse = $finder->__invoke($finderRequest);

        return new JsonResponse(
            [
                'id' => $finderResponse->id(),
                'status' => $finderResponse->status(),
                'totalPrice' => $finderResponse->totalPrice()
            ],
            Response::HTTP_FOUND
        );
    }


    private function constraints(): Assert\Collection
    {
        return new Assert\Collection(
            [

            ]
        );
    }
}

<?php

namespace App\Orders\Infrastructure\Controllers;


use App\Orders\Application\Create\DTO\CreateOrderRequest;
use App\Orders\Application\Create\OrderCreator;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class OrderPostController extends ApiController
{
    public function __invoke(Request $request, OrderCreator $orderCreator): JsonResponse
    {
        $request_data = json_decode($request->getContent(), true);

        $this->validateRequest($request_data, $this->constraints());

        // I generate the UUID in the controller for Testing and consistency proposes
        $orderId = Uuid::generate()->getValue();

        $orderRequest = new CreateOrderRequest(
            $orderId
        );

        $orderCreator->__invoke($orderRequest);

        return new JsonResponse(["order_id" => $orderId], Response::HTTP_CREATED);
    }


    private function constraints(): Assert\Collection
    {
        return new Assert\Collection(
            [

            ]
        );
    }
}

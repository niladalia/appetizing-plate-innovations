<?php

namespace App\Orders\Infrastructure\Controllers;


use App\Orders\Application\AddItem\DTO\ItemAdderRequest;
use App\Orders\Application\AddItem\ItemAdder;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class OrderItemPostController extends ApiController
{
    public function __invoke(Request $request,string $id, ItemAdder $itemAdder): JsonResponse
    {
        $request_data = json_decode($request->getContent(), true);

        $this->validateRequest($request_data, $this->constraints());

        $itemAdderRequest = new ItemAdderRequest(
            Uuid::generate()->getValue(),
            $id,
            $request_data['item_id'],
            $request_data['quantity']
        );

        $itemAdder->__invoke($itemAdderRequest);

        return new JsonResponse([], Response::HTTP_CREATED);
    }


    private function constraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'item_id' => [new Assert\NotBlank(), new Assert\Length(['min' => 36, 'max' => 36])],
                'quantity' => [new Assert\NotBlank(), new Assert\Type('numeric')]
            ]
        );
    }
}

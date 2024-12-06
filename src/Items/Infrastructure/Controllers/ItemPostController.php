<?php

namespace App\Items\Infrastructure\Controllers;


use App\Items\Application\Create\DTO\CreateItemRequest;
use App\Items\Application\Create\ItemCreator;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class ItemPostController extends ApiController
{
    public function __invoke(Request $request, ItemCreator $itemCreator): JsonResponse
    {
        $request_data = json_decode($request->getContent(), true);

        $this->validateRequest($request_data, $this->constraints());

        // I generate the UUID in the controller for Testing and consistency proposes
        $itemId = Uuid::generate()->getValue();

        $itemRequest = new CreateItemRequest(
            $itemId,
            $request_data['name'],
            $request_data['description'],
            $request_data['price']
        );

        $itemCreator->__invoke($itemRequest);

        return new JsonResponse(["item_id" => $itemId], Response::HTTP_CREATED);
    }


    private function constraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'name' => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 255])],
                'description' => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 255])],
                'price' => [ new Assert\Optional([new Assert\LessThanOrEqual(99), new Assert\GreaterThanOrEqual(0)])]
            ]
        );
    }
}

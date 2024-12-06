<?php

namespace App\Orders\Infrastructure\Controllers;


use App\Orders\Application\ChangeStatus\DTO\ChangeStatusRequest;
use App\Orders\Application\ChangeStatus\OrderStatusUpdater;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class OrderChangeStatusController extends ApiController
{
    public function __invoke(Request $request,string $id, OrderStatusUpdater $orderUpdater): JsonResponse
    {
        $request_data = json_decode($request->getContent(), true);

        $this->validateRequest($request_data, $this->constraints());

        $orderRequest = new ChangeStatusRequest(
            $id,
            $request_data['status']
        );

        $orderUpdater->__invoke($orderRequest);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }


    private function constraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'status' => [new Assert\NotBlank(), new Assert\Type('string')]
            ]
        );
    }
}

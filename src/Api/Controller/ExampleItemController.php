<?php

namespace App\Api\Controller;

use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Api\Entity\ExampleItem;
use Symfony\Component\Uid\Uuid;

class ExampleItemController extends BaseController
{
    protected $jsonContext = ['groups' => ['example_item:read']];

    #[Route('/api/example_item', name: 'api_get_example_items', format: 'json', methods: ['GET'])]
    public function get_example_items(): JsonResponse
    {
        $items = $this->em->getRepository(ExampleItem::class)
            ->findAll();
        return $this->json($items);
    }

    #[Route('/api/example_item/{uuid}', name: 'api_get_example_item_by_uuid', format: 'json', methods: ['GET'])]
    public function get_example_item_by_uuid(Uuid $uuid): JsonResponse
    {
        $item = $this->em->getRepository(ExampleItem::class)
            ->find($uuid);
        return $this->jsonWithGroups($item, ['example_item:read', 'example_item:example_category:read', 'example_category:read', 'version:read']);
    }
}

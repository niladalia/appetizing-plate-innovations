<?php

namespace App\Api\Controller;

use App\Api\Dto\ExampleCategoryDto;
use App\Shared\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Api\Entity\ExampleCategory;
use Symfony\Component\Uid\Uuid;

class ExampleCategoryController extends BaseController
{
    protected $jsonContext = ['groups' => ['example_category:read']];

    #[Route('/api/example_category', name: 'api_get_example_categories', format: 'json', methods: ['GET'])]
    public function get_example_categories(): JsonResponse
    {
        $items = $this->em->getRepository(ExampleCategory::class)
            ->findAll();
        return $this->json($items);
    }

    #[Route('/api/example_category/{uuid}', name: 'api_get_example_category_by_uuid', format: 'json', methods: ['GET'])]
    public function get_example_category_by_uuid(Uuid $uuid): JsonResponse
    {
        $item = $this->em->getRepository(ExampleCategory::class)
            ->find($uuid);
        return $this->jsonWithGroups($item, ['example_category:read', 'example_category:example_item:read', 'example_item:read', 'version:read']);
    }

    #[Route('/api/example_category/{uuid}/items', name: 'api_get_example_category_items_by_uuid', format: 'json', methods: ['GET'])]
    public function get_example_category_items_by_uuid(Uuid $uuid): JsonResponse
    {
        $items = $this->em->getRepository(ExampleCategory::class)
            ->find($uuid)
            ->getExampleItems();
        return $this->jsonWithOnlyGroups($items, ['example_item:read']);
    }

    #[Route('/api/example_category/{uuid}', name: 'api_update_example_category_by_uuid', format: 'json', methods: ['PUT'])]
    public function update_example_category_by_uuid(
        Uuid $uuid,
        #[MapRequestPayload(validationGroups: ["update"])] ExampleCategoryDto $dto,
    ): JsonResponse
    {
        $repository = $this->em->getRepository(ExampleCategory::class);
        $item = $repository->find($uuid);
        $dto->update($item);
        $repository->flush();

        return $this->jsonWithGroups($item, ['version:read']);
    }

    #[Route('/api/example_category', name: 'api_create_example_category', format: 'json', methods: ['POST'])]
    public function create_example_category(
        #[MapRequestPayload(validationGroups: ["create"])] ExampleCategoryDto $dto,
    ): JsonResponse
    {
        $item = $dto->update();
        $this->em->getRepository(ExampleCategory::class)
            ->persist($item)
            ->flush();
        return $this->json($item);
    }

    #[Route('/api/example_category/{uuid}', name: 'api_delete_example_category_by_uuid', format: 'json', methods: ['DELETE'])]
    public function delete_example_category_by_uuid(
        Uuid $uuid,
    ): JsonResponse
    {
        $this->em->getRepository(ExampleCategory::class)
            ->remove($uuid)
            ->flush();
        return $this->json([], 204);
    }
}

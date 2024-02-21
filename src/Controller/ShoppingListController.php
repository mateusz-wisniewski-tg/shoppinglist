<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\CreateShoppingList;
use App\Controller\Request\PatchShoppingList;
use App\Controller\Response\GetCollectionShoppingListResponse;
use App\Controller\Response\GetShoppingListItemResponse;
use App\Entity\ShoppingList;
use App\Repository\ShoppingListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'Shopping List')]
#[Route('api/shopping-list')]
class ShoppingListController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private ShoppingListRepository $shoppingListRepository,
    )
    {
    }

    #[Route('/', methods: ['post'])]
    public function create(#[MapRequestPayload] CreateShoppingList $request): Response
    {
        $this->entityManager->persist(
            (new ShoppingList())
                ->setName($request->name)
                ->setFulfilled($request->fulfilled)
                ->setUser($this->getUser())
        );
        $this->entityManager->flush();

        return new JsonResponse();
    }

    #[Route('/', methods: ['get'])]
    public function getCollection(): Response
    {
        $response = [];
        $shoppingLists = $this->shoppingListRepository->findForUser($this->getUser());
        foreach ($shoppingLists as $shoppingList) {
            $shoppingListItems = [];
            foreach ($shoppingList->getShoppingListItems() as $shoppingListItem) {
                $shoppingListItems[] = new GetShoppingListItemResponse($shoppingListItem->getId(), $shoppingListItem->getName(), $shoppingListItem->getQuantity(), $shoppingListItem->getQuantityUnit(), $shoppingListItem->isPurchased());
            }
            $response['owned'][] = new GetCollectionShoppingListResponse($shoppingList->getId(), $shoppingList->getName(), $shoppingList->isFulfilled(), $shoppingListItems);
        }
        return new JsonResponse($response);
    }

    #[Route('/{id}', methods: ['get'])]
    public function get(ShoppingList $shoppingList): Response
    {
        if($shoppingList->getUser() === $this->getUser()) {
            $shoppingListItems = [];
            foreach ($shoppingList->getShoppingListItems() as $shoppingListItem) {
                $shoppingListItems[] = new GetShoppingListItemResponse($shoppingListItem->getId(), $shoppingListItem->getName(), $shoppingListItem->getQuantity(), $shoppingListItem->getQuantityUnit(), $shoppingListItem->isPurchased());
            }
            $response = new GetCollectionShoppingListResponse($shoppingList->getId(), $shoppingList->getName(), $shoppingList->isFulfilled(), $shoppingListItems);

            return new JsonResponse($response);
        }
        return new JsonResponse("Not found", 404);
    }


    #[Route('/{id}', methods: ['delete'])]
    public function delete(ShoppingList $shoppingList): Response
    {
        if($shoppingList->getUser() === $this->getUser()){
            $this->entityManager->remove($shoppingList);
            $this->entityManager->flush();

            return new JsonResponse();
        }
        return new JsonResponse("Not the owner of that shopping list", 400);
    }

    #[Route('/', methods: ['patch'])]
    public function patch(#[MapRequestPayload] PatchShoppingList $request): Response
    {
        $shoppingList = $this->shoppingListRepository->find($request->shoppingListId);
        if(null !== $shoppingList && $shoppingList->getUser() === $this->getUser()){
            $shoppingList->setName($request->name);
            $shoppingList->setFulfilled($request->fulfilled);
            $this->entityManager->flush();

            return new JsonResponse();
        }

        return new JsonResponse("Not found or not an owner", 404);
    }
}

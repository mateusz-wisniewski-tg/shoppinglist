<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\CreateShoppingListItem;
use App\Controller\Request\PatchShoppingListItem;
use App\Entity\ShoppingListItem;
use App\Repository\ShoppingListItemRepository;
use App\Repository\ShoppingListRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Shopping List Items')]
#[Route('api/shopping-list/items')]
class ShoppingListItemController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private ShoppingListItemRepository $shoppingListItemRepository,
                                private ShoppingListRepository $shoppingListRepository,
    )
    {
    }

    #[Route('/', methods: ['post'])]
    public function create(#[MapRequestPayload] CreateShoppingListItem $request): Response
    {
        try {
            $this->entityManager->persist(
                (new ShoppingListItem())
                    ->setName($request->name)
                    ->setPurchased($request->purchased)
                    ->setQuantity($request->quantity)
                    ->setQuantityUnit($request->quantityUnit)
                    ->setShoppingList($this->shoppingListRepository->find($request->shoppingListId))
            );
            $this->entityManager->flush();

        }
        catch (UniqueConstraintViolationException $exception){
            return new JsonResponse("Non unique entry", 400
            );
        }
        return new JsonResponse();
    }

    #[Route('/{id}', methods: ['delete'])]
    public function delete(ShoppingListItem $shoppingListItem): Response
    {
        if($shoppingListItem->getShoppingList()->getUser() === $this->getUser()){
            $this->entityManager->remove($shoppingListItem);
            $this->entityManager->flush();

            return new JsonResponse();
        }
        return new JsonResponse("Not the owner of that shopping list item", 400);
    }

    #[Route('/', methods: ['patch'])]
    public function patch(#[MapRequestPayload] PatchShoppingListItem $request): Response
    {
        $shoppingListItem = $this->shoppingListItemRepository->find($request->shoppingListItemId);
        if(null !== $shoppingListItem && $shoppingListItem->getShoppingList()->getUser() === $this->getUser()){
            $shoppingListItem->setName($request->name);
            $shoppingListItem->setPurchased($request->purchased);
            $shoppingListItem->setQuantity($request->quantity);
            $shoppingListItem->setQuantityUnit($request->quantityUnit);
            $this->entityManager->flush();

            return new JsonResponse();
        }

        return new JsonResponse("Not found or not an owner", 404);
    }
}

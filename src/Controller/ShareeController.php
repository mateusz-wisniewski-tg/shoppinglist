<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\CreateSharee;
use App\Controller\Request\CreateShoppingList;
use App\Entity\Sharee;
use App\Entity\ShoppingList;
use App\Repository\ShareeRepository;
use App\Repository\ShoppingListRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Sharee')]
#[Route('api/sharee')]
class ShareeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private UserRepository $userRepository,
                                private ShoppingListRepository $shoppingListRepository,
    )
    {
    }

    #[Route('/', methods: ['post'])]
    public function create(#[MapRequestPayload] CreateSharee $request): Response
    {
        $user = $this->userRepository->findOneBy(['email' => $request->email]);
        $shoppingList = $this->shoppingListRepository->find([$request->shoppingListId]);
        if(null !== $user && null !== $shoppingList && $shoppingList->getUser() === $this->getUser()){
            $this->entityManager->persist(
                (new Sharee())
                    ->setShoppingList($shoppingList)
                    ->setUser($user)
                    ->setReadOnly($request->readonly)
                    ->setOwner($this->getUser())
            );
            $this->entityManager->flush();

            return new JsonResponse();
        }
        return new JsonResponse("Not found", 404);
    }

    #[Route('/{id}', methods: ['delete'])]
    public function delete(Sharee $sharee): Response
    {
        if($sharee->getOwner() === $this->getUser()){
            $this->entityManager->remove($sharee);
            $this->entityManager->flush();

            return new JsonResponse();
        }
        return new JsonResponse("Not the owner of that sharee", 400);
    }
}

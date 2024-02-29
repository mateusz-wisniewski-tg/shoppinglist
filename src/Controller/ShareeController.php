<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\CreateSharee;
use App\Controller\Response\GetCollectionShareeResponse;
use App\Entity\Sharee;
use App\Entity\User;
use App\Repository\ShareeRepository;
use App\Repository\ShoppingListRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use TGF\PartnerErrors\Domain\Model\PartnerError;

#[OA\Tag(name: 'Sharee')]
#[Route('api/sharee')]
class ShareeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private ShareeRepository $shareeRepository,
                                private UserRepository $userRepository,
                                private ShoppingListRepository $shoppingListRepository,
    )
    {
    }

    #[Route('/', methods: ['post'])]
    public function create(#[MapRequestPayload] CreateSharee $request): Response
    {
        $user = null;
        if(null !== $request->email){
            $user = $this->userRepository->findOneBy(['email' => $request->email]);
        } else if(null !== $request->username)
        {
            $user = $this->userRepository->findOneBy(['username' => $request->username]);
        } else {
            return new JsonResponse("Invalid sharee credentials");
        }
        $shoppingList = $this->shoppingListRepository->find($request->shoppingListId);
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
    public function delete(User $user): Response
    {
            $this->shareeRepository->deleteByUserAndOwner($user, $this->getUser());
            $this->entityManager->flush();

            return new JsonResponse();
    }

    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Rejection reason lookup response.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: GetCollectionShareeResponse::class)),
        )
    )]
    #[Route('/', methods: ['get'])]
    public function getCollection(): Response
    {
        $parsedSharees = [];
        $response = [];
        $sharees = $this->shareeRepository->findBy(["owner" => $this->getUser()]);
        foreach ($sharees as $sharee){
            $parsedSharees[$sharee->getUser()?->getId()]['shoppingLists'][] = $sharee->getShoppingList()?->getId();
        }
        $duplicateIds = [];
        foreach ($sharees as $sharee){
            if(!array_key_exists($sharee->getUser()?->getId(), $duplicateIds)) {
                $response[] = new GetCollectionShareeResponse(
                    shareeId: $sharee->getUser()?->getId(),
                    shareeUsername: $sharee->getUser()?->getUsername(),
                    shareeEmail: $sharee->getUser()?->getEmail(),
                    shareeShoppingListIds: $parsedSharees[$sharee->getUser()?->getId()]['shoppingLists']
                );
                $duplicateIds[$sharee->getUser()?->getId()] = true;
            }
        }
        return new JsonResponse($response);
    }
}

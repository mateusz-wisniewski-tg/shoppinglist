<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\RegisterUserRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;


#[OA\Tag(name: 'User')]
class UserController extends AbstractController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/api/user/register', name: 'register_user', methods: ['post'])]
    public function registerUser(#[MapRequestPayload] RegisterUserRequest $request): Response
    {
        $user = new User();
        $user->setUsername($request->username);
        $user->setEmail($request->email);
        $plaintextPassword = $request->password;

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse("");
    }
}

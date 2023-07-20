<?php

namespace App\Controller\API;

use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Main')]
#[Security(name: 'Bearer')]
#[Route('/api/main/', name: 'api_main_')]
class MainController extends AbstractController
{


    #[OA\Tag(name: 'Account')]
    #[Security(name: 'Bearer')]
    #[Route('account', name: 'account')]
    public function account(UserRepository $userRepository): JsonResponse
    {
        $user = $this->getUser();

        return $this->json($userRepository->findOneBy(['id'=>$user->getId()]), 200, [], ['groups' => 'read']);
    }

}


<?php

namespace App\Controller\API;

use App\Repository\BotanistRepository;
use App\Repository\PlanteRepository;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


#[Route('/api/main/', name: 'api_main_')]
class MainController extends AbstractController
{



    #[Route('account', name: 'account')]
    public function account(UserRepository $userRepository): JsonResponse
    {
        $user = $this->getUser();

        return $this->json($userRepository->findOneBy(['id'=>$user->getId()]), 200, [], ['groups' => 'read']);
    }



    #[Route('botaniste', name: 'botaniste')]
    public function botaniste(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll(), 200, [], ['groups' => 'read']);
    }




    #[Route('upload', name: 'upload',methods: ["POST"])]
    public function uploadPhoto(Request $request): Response
    {
        // Retrieve the uploaded file from the request
        $uploadedFile = $request->files->get('photo');

        // Check if a file was actually uploaded
        if ($uploadedFile === null) {
            return new Response('No file uploaded', Response::HTTP_BAD_REQUEST);
        }

        // Process and save the file
        $destinationPath = '/uploads'; // Specify your desired destination path
        $newFilename = md5(uniqid()) . '.' . $uploadedFile->getClientOriginalExtension();

        try {
            $uploadedFile->move($destinationPath, $newFilename);
        } catch (\Exception $e) {
            return new Response('Failed to save the uploaded file', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Return a success response
        return new Response('File uploaded successfully', Response::HTTP_OK);
    }

}


<?php

namespace App\Controller\API;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/chat', name: 'api_chat_')]
class ChatController extends AbstractController
{
    #[Route('/chat', name: 'app_chat')]
    public function index(): Response
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }

    #[Route('/messages', name: 'chat_messages', methods: ['POST','GET'])]
    public function chatMessages(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $message = $content['message'];

        // Process the message (e.g., store it in the database, broadcast it to other users, etc.)

        // Return a JSON response indicating the success or failure of the message processing
        return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
    }
}

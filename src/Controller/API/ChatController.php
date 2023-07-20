<?php

namespace App\Controller\API;


use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/chat', name: 'api_chat_')]
class ChatController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/chats/{id}', name: 'list_chats', methods: ["GET"])]
    public function listChats(SerializerInterface $serializer, $id, UserRepository $userRepository, ConversationRepository $conversationRepository)
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        // Assuming your Conversation entity has properties named "startedBy" and "targetUser"
        $conversations = $conversationRepository->createQueryBuilder('c')
            ->where('c.startedBy = :user OR c.targetUser = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $this->json($conversations, 200, [], ['groups' => 'read']);
    }



    #[Route('/start_conv/{id}', name: 'start_conv', methods: ["POST"])]
    public function startConv(Request $request, UserRepository $userRepository, $id)
    {
        // Get the current user who started the conversation
        $startedBy = $this->getUser();

        // Get the target user based on the provided targetUserId in the request body
        $targetUser = $userRepository->findOneBy(['id' => $id]);

        // Check if a conversation already exists between the users in both directions
        $existingConversation1 = $this->entityManager->getRepository(Conversation::class)->findOneBy([
            'startedBy' => $startedBy,
            'targetUser' => $targetUser,
        ]);

        $existingConversation2 = $this->entityManager->getRepository(Conversation::class)->findOneBy([
            'startedBy' => $targetUser,
            'targetUser' => $startedBy,
        ]);

        // If a conversation already exists in either direction, return a message indicating it
        if ($existingConversation1 || $existingConversation2) {
            return new JsonResponse(['message' => 'A conversation already exists between these users.'], JsonResponse::HTTP_CONFLICT);
        }

        // Create a new conversation
        $conversation = new Conversation();
        $conversation->setStartedBy($startedBy);
        $conversation->setTargetUser($targetUser);

        // Persist the conversation to the database
        $this->entityManager->persist($conversation);
        $this->entityManager->flush();

        // Convert the conversation to JSON and send the response
        $response = [
            'id' => $conversation->getId(),
            'startedBy' => [
                'id' => $startedBy->getId(),
                'username' => $startedBy->getUsername(),
                // Add any other properties you want to include
            ],
            'targetUser' => [
                'id' => $targetUser->getId(),
                'username' => $targetUser->getUsername(),
                // Add any other properties you want to include
            ],
            // Add any other properties you want to include in the response
        ];

        return new JsonResponse($response, JsonResponse::HTTP_CREATED);
    }


    #[Route('/messages/{id}', name: 'messages', methods: ['GET'])]
    public function chatMessages(ConversationRepository $conversationRepository,SerializerInterface $serializer,$id,UserRepository $userRepository,MessageRepository $messageRepository)
    {
        $conv = $conversationRepository->findOneBy(['id' => $id]);
        return $this->json($messageRepository->findBy(["conversation" => $conv]), 200, [], ['groups' => 'read']);
    }



    #[Route('/send-message/{id}', name: 'send_message', methods: ["POST"])]
    public function sendMessage(ConversationRepository $conversationRepository,Request $request, UserRepository $userRepository, $id)
    {
        // Get the current user who is the sender of the message
        $sender = $this->getUser();
        $conv = $conversationRepository->findOneBy(['id' => $id]);

        // Get the recipient based on the provided recipientId in the URL
        $recipient = $userRepository->findOneBy(['id' => $conv->getStartedBy()]);

        // Find the conversation between the sender and recipient
//        $conversation = $this->entityManager->getRepository(Conversation::class)->findOneBy([
//            'startedBy' => $sender,
//            'targetUser' => $recipient,
//        ]);
//
//        $conversation = $this->entityManager->getRepository(Conversation::class)->findOneBy([
//            'startedBy' => $recipient,
//            'targetUser' => $sender,
//        ]);
//        // Check if a conversation exists between the users
//        if (!$conversation) {
//            return new JsonResponse(['message' => 'No conversation found between the users.'], JsonResponse::HTTP_NOT_FOUND);
//        }

        // Get the content of the message from the request body
        $content = $request->getContent();
        $requestData = json_decode($content, true);

        // Check if the content is provided and not empty
        if (!isset($requestData['content']) || empty($requestData['content'])) {
            return new JsonResponse(['message' => 'Message content is required.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Create a new message
        $message = new Message();
        $message->setContent($requestData['content']);
        $message->setSender($sender);
        $message->setRecipient($recipient);
        $message->setConversation($conv);
        $message->setIsRead(false);
        $message->setTimestampsOnCreate();

        // Persist the message to the database
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        // Convert the message to JSON and send the response
        $response = [
            'id' => $message->getId(),
            'content' => $message->getContent(),
            'sender' => [
                'id' => $sender->getId(),
                'username' => $sender->getUsername(),
                // Add any other properties you want to include
            ],
            'recipient' => [
                'id' => $recipient->getId(),
                'username' => $recipient->getUsername(),
                // Add any other properties you want to include
            ],
            // Add any other properties you want to include in the response
        ];

        return new JsonResponse($response, JsonResponse::HTTP_CREATED);
    }


}

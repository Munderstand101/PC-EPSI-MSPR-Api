<?php

namespace App\Controller\API;

use App\Entity\Botanist;
use App\Form\BotanistType;
use App\Repository\BotanistRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


#[Route('/api/botanist', name: 'api_botanist_')]
class BotanistApiController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'botaniste')]
    public function botaniste(BotanistRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll(), 200, [], ['groups' => 'read']);
    }

   /* #[Route('/', name: 'api_botanist_index', methods: ['GET'])]
    public function index(BotanistRepository $botanistRepository): Response
    {
        $botanists = $botanistRepository->findAll();

        $jsonContent = $this->serializer->serialize($botanists, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['createdAt', 'updatedAt'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return new Response($jsonContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }*/

    #[Route('/new', name: 'api_botanist_new', methods: ['POST'])]
    public function new(Request $request, BotanistRepository $botanistRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $botanist = new Botanist();
        $form = $this->createForm(BotanistType::class, $botanist);
        $form->submit($data);

        if ($form->isValid()) {
            $botanistRepository->save($botanist, true);

            $jsonContent = $this->serializer->serialize($botanist, 'json', [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['createdAt', 'updatedAt'],
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                },
            ]);

            return new Response($jsonContent, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
        }

        $errors = $this->getFormErrors($form);

        return new Response(json_encode(['errors' => $errors]), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'api_botanist_show', methods: ['GET'])]
    public function show(Botanist $botanist): Response
    {
        $jsonContent = $this->serializer->serialize($botanist, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['createdAt', 'updatedAt'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ]);

        return new Response($jsonContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}/edit', name: 'api_botanist_edit', methods: ['PUT'])]
    public function edit(Request $request, Botanist $botanist, BotanistRepository $botanistRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(BotanistType::class, $botanist);
        $form->submit($data);

        if ($form->isValid()) {
            $botanistRepository->save($botanist, true);

            $jsonContent = $this->serializer->serialize($botanist, 'json', [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['createdAt', 'updatedAt'],
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                },
            ]);

            return new Response($jsonContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        $errors = $this->getFormErrors($form);

        return new Response(json_encode(['errors' => $errors]), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'api_botanist_delete', methods: ['DELETE'])]
    public function delete(Request $request, Botanist $botanist, BotanistRepository $botanistRepository): Response
    {
        $botanistRepository->remove($botanist, true);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    private function getFormErrors($form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }
}

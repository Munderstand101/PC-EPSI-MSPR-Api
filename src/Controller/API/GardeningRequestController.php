<?php

namespace App\Controller\API;

use App\Entity\Gardening;
use App\Repository\PlanteRepository;
use App\Repository\UserRepository;
use App\Repository\GardeningRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

#[Route('/api/requests', name: 'api_requests_')]
class GardeningRequestController extends AbstractController
{

    #[Route('/', name: 'getAll', methods: ['GET'])]
    public function getAll(GardeningRepository $gardeningRepository, SerializerInterface $serializer): Response
    {
        return $this->json($gardeningRepository->findAll(), 200, [], ['groups' => 'read']);
    }

    #[Route('/my/{id}', name: 'index', methods: ['GET'])]
    public function index(GardeningRepository $gardeningRepository, SerializerInterface $serializer,$id,UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=>$id]);
        return $this->json($gardeningRepository->findBy(["user"=>$user]), 200, [], ['groups' => 'read']);
    }

    #[Route('/new', name: 'app_gardening_request')]
    public function new(Request $request, ValidatorInterface $validator, GardeningRepository $gardeningRepository, PlanteRepository $planteRepository, UserRepository $userRepository, SerializerInterface $serializer): Response
    {

        $payload = json_decode($request->getContent(), true);

        $gardeningRequest = new Gardening();
        $gardeningRequest->setTitle($payload['title']);
        $gardeningRequest->setDescription($payload['description']);

        $gardeningRequest->setDateDebut(new DateTime($payload['date_debut'] ?? ''));

        $gardeningRequest->setDateFin(new DateTime($payload['date_fin'] ?? ''));


        $gardeningRequest->setAddress($payload['address']);
        $gardeningRequest->setUser($userRepository->findOneBy(["id"=>$payload['user_id']]));
        $gardeningRequest->setPlant($planteRepository->findOneBy(["id"=>$payload['plant_id']]));

        $errors = $validator->validate($gardeningRequest);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            $json = $serializer->serialize(['errors' => $errorMessages], 'json');
            return new Response($json, Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $gardeningRepository->save($gardeningRequest, true);
       // $json = $serializer->serialize($gardeningRequest, 'json');
        return $this->json([
            'message' => 'La requête de gardiennage a été ajoutée avec succès',

        ]);
      //  return new Response($json, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_plante_delete', methods: ['DELETE'])]
    public function delete(Request $request, Gardening $gardening, GardeningRepository $gardeningRepository): Response
    {
        $gardeningRepository->remove($gardening, true);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}

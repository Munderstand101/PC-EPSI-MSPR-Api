<?php

namespace App\Controller\API;

use App\Entity\Gardening;
use App\Repository\PlanteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GardeningRequestController extends AbstractController
{
    #[Route('/gardening/request', name: 'app_gardening_request')]
    public function createGardeningRequest(Request $request, PlanteRepository $planteRepository): Response
    {

        $payload = json_decode($request->getContent(), true);
//        $payload['user_id']
//        $payload['plant_id']

        $gardeningRequest = new Gardening();
        $gardeningRequest->setUser($this->getUser());
        $gardeningRequest->setPlant($planteRepository->findOneBy(['id'=>$payload['plant_id']]));

        // Enregistrez la demande de garde dans la base de données

        return $this->json(['message' => 'Gardening request created successfully'], Response::HTTP_CREATED);
    }
}

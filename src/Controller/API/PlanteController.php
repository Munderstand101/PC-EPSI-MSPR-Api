<?php

namespace App\Controller\API;

use App\Entity\Plante;
use App\Entity\User;
use App\Form\PlanteType;
use App\Repository\PlanteRepository;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api/plante', name: 'api_plante_')]
class PlanteController extends AbstractController
{


    #[Route('/{id}', name: 'index', methods: ['GET'])]
    public function index(PlanteRepository $planteRepository, SerializerInterface $serializer,$id,UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=>$id]);
        return $this->json($planteRepository->findBy(["user"=>$user]), 200, [], ['groups' => 'read']);
    }


    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(Request $request, ValidatorInterface $validator, PlanteRepository $planteRepository, SerializerInterface $serializer): Response
    {
        $payload = json_decode($request->getContent(), true);

        $plante = new Plante();
        $plante->setName($payload['name']);
        $plante->setDescription($payload['description']);

        $errors = $validator->validate($plante);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            $json = $serializer->serialize(['errors' => $errorMessages], 'json');
            return new Response($json, Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $planteRepository->save($plante, true);
        $json = $serializer->serialize($plante, 'json');

        return new Response($json, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/add', name: 'add', methods: ['POST'])]
    public function addPlant(Request $request, PlanteRepository $planteRepository, UserRepository $userRepository): Response
    {
        // Récupérer les données de la requête
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $photo = $request->files->get('photo');
        $userId = $request->request->get('user_id');
        $user = $userRepository->findOneBy(["id"=>$userId]);

        // Vérifier si une photo a été envoyée
        if ($photo) {
            // Gérer le téléchargement de la photo (par exemple, en la sauvegardant dans un dossier)
            $photoName = $this->generateUniqueFileName().'.'.$photo->guessExtension();
            $photo->move(
                $this->getParameter('photos_directory'),
                $photoName
            );

            // Enregistrer le nom de la photo dans la base de données
            // Vous devrez créer une entité "Plant" avec une propriété "photo" pour stocker le nom du fichier
            $plant = new Plante();
            $plant->setName($name);
            $plant->setDescription($description);
            $plant->setPhoto($photoName);
            $plant->setUser($user);

            // Enregistrer l'entité dans la base de données
            $planteRepository->save($plant, true);
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($plant);
//            $entityManager->flush();

            // Retourner une réponse JSON avec les détails de la plante ajoutée
            return $this->json([
                'message' => 'La plante a été ajoutée avec succès',
                'plant' => [
                    'id' => $plant->getId(),
                    'name' => $plant->getName(),
                    'photo' => $plant->getPhoto(),
                ],
            ]);
        }

        // Retourner une erreur si aucune photo n'a été envoyée
        return $this->json(['error' => 'Aucune photo n\'a été envoyée.'], Response::HTTP_BAD_REQUEST);
    }

    // Fonction pour générer un nom de fichier unique pour la photo
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }


    #[Route('/{id}', name: 'app_plante_show', methods: ['GET'])]
    public function show(Plante $plante, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($plante, 'json');

        return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}/edit', name: 'app_plante_edit', methods: ['PUT'])]
    public function edit(Request $request, Plante $plante, PlanteRepository $planteRepository, SerializerInterface $serializer): Response
    {
        $form = $this->createForm(PlanteType::class, $plante);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $planteRepository->save($plante, true);
            $json = $serializer->serialize($plante, 'json');

            return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        $errors = $this->getFormErrors($form);
        $json = $serializer->serialize(['errors' => $errors], 'json');

        return new Response($json, Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'app_plante_delete', methods: ['DELETE'])]
    public function delete(Request $request, Plante $plante, PlanteRepository $planteRepository): Response
    {
        $planteRepository->remove($plante, true);

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

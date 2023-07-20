<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\BackOfficeAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class RegistrationController extends AbstractController
{



    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'User registration data',
        required: true,
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new User())
        )

    )]
    #[OA\Response(
        response: 201,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new User())
        )
    )]
    #[OA\Parameter(
        name: 'order',
        description: 'The field used to order rewards',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'User Registration')]
    #[Security(name: 'Bearer')]
    public function registerAPI(Request $request, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the JSON payload from the request
        $payload = json_decode($request->getContent(), true);

        // Create a new instance of the User entity
        $user = new User();

        $user->setFirstName($payload['firstName']);
        $user->setLastName($payload['lastName']);
        $user->setRoles(['ROLE_USER']); // Set default roles

        $user->setUsername($payload['username']);
        $user->setEmail($payload['email']);

        $user->setAddress($payload['address']);
        $user->setZipcode($payload['zipcode']);
        $user->setCity($payload['city']);

        $user->setLatitude($payload['latitude']);
        $user->setLongitude($payload['longitude']);

        $user->setTimestampsOnCreate();

        // Validate the user entity
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            // Handle validation errors
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            // Return a JSON response with the validation errors
            return new Response(json_encode(['errors' => $errorMessages]), Response::HTTP_BAD_REQUEST);
        }

        // Hash and set the user's password
        $hashedPassword = $passwordHasher->hashPassword($user, $payload['password']);
        $user->setPassword($hashedPassword);

        // Persist the user entity in the database
        $entityManager->persist($user);
        $entityManager->flush();

        // Return a JSON response indicating successful registration
        return new Response(json_encode(['message' => 'User registered successfully']), Response::HTTP_CREATED);
    }

}

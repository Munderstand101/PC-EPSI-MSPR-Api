<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use OpenApi\Attributes as OA;
#[Route('/user')]
#[OA\Tag(name: 'User')]
class UserController extends AbstractController
{
    #[Route('/createWithArray', name: 'create_with_array', methods: ['POST'])]
    #[OA\Post(
        path: '/createWithArray',
        operationId: 'createWithArray',
        summary: 'Creates a list of users with given input array',
        requestBody: [
            'description' => 'List of users',
            'required' => true,
            'content' => [
                'application/json' => [
                    'schema' => [
                        'type' => 'array',
                        'items' => [
                            '$ref' => '#/components/schemas/User'
                        ]
                    ]
                ]
            ]
        ],
        tags: ['User'],
        responses: [
            'default' => [
                'description' => 'Successful operation'
            ]
        ]
    )]
    public function createWithArray(Request $request): Response
    {
        // Process the request and create users with the provided input array
        // ...

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/createWithList', name: 'create_with_list', methods: ['POST'])]
    #[OA\Post(
        path: '/createWithList',
        operationId: 'createWithList',
        summary: 'Creates a list of users with given input array',
        requestBody: [
            'description' => 'List of users',
            'required' => true,
            'content' => [
                'application/json' => [
                    'schema' => [
                        'type' => 'array',
                        'items' => [
                            '$ref' => '#/components/schemas/User'
                        ]
                    ]
                ]
            ]
        ],
        tags: ['User'],
        responses: [
            'default' => [
                'description' => 'Successful operation'
            ]
        ]
    )]
    public function createWithList(Request $request): Response
    {
        // Process the request and create users with the provided input list
        // ...

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/{username}', name: 'get_user', methods: ['GET'])]
    #[OA\Get(
        path: '/{username}',
        operationId: 'getUser',
        summary: 'Get user by user name',
        tags: ['User'],
        parameters: [
            [
                'name' => 'username',
                'in' => 'path',
                'description' => 'Username of the user',
                'required' => true,
                'schema' => [
                    'type' => 'string'
                ]
            ]
        ],
        responses: [
            'default' => [
                'description' => 'Successful operation'
            ]
        ]
    )]
    public function getUse1r(string $username): Response
    {
        // Retrieve the user information based on the provided username
        // ...

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/{username}', name: 'update_user', methods: ['PUT'])]
    #[OA\Put(
        path: '/{username}',
        operationId: 'updateUser',
        summary: 'Update user',
        requestBody: [
            'description' => 'Updated user object',
            'required' => true,
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/User'
                    ]
                ]
            ]
        ],
        tags: ['User'],
        parameters: [
            [
                'name' => 'username',
                'in' => 'path',
                'description' => 'Username of the user',
                'required' => true,
                'schema' => [
                    'type' => 'string'
                ]
            ]
        ],
        responses: [
            'default' => [
                'description' => 'Successful operation'
            ]
        ]
    )]
    public function updateUser(string $username, Request $request): Response
    {
        // Update the user information based on the provided username and request body
        // ...

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/{username}', name: 'delete_user', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/{username}',
        operationId: 'deleteUser',
        tags: ['User'],
        summary: 'Delete user',
        parameters: [
            [
                'name' => 'username',
                'in' => 'path',
                'description' => 'Username of the user',
                'required' => true,
                'schema' => [
                    'type' => 'string'
                ]
            ]
        ],
        responses: [
            'default' => [
                'description' => 'Successful operation'
            ]
        ]
    )]
    public function deleteUser(string $username): Response
    {
        // Delete the user based on the provided username
        // ...

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/login', name: 'login', methods: ['GET'])]
    #[OA\Get(
        path: '/login',
        operationId: 'login',
        tags: ['User'],
        summary: 'Log user into the system',
        responses: [
            'default' => [
                'description' => 'Successful operation'
            ]
        ]
    )]
    public function login(): Response
    {
        // Log the user into the system
        // ...

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    #[OA\Get(
        path: '/logout',
        operationId: 'logout',
        tags: ['User'],
        summary: 'Log out current logged in user session',
        responses: [
            'default' => [
                'description' => 'Successful operation'
            ]
        ]
    )]
    public function logout(): Response
    {
        // Log out the current logged in user session
        // ...

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_user', methods: ['POST'])]
    #[OA\Post(
        path: '/',
        operationId: 'createUser',
        summary: 'Create user',
        requestBody: [
            'description' => 'User object',
            'required' => true,
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/User'
                    ]
                ]
            ]
        ],
        tags: ['User'],
        responses: [
            'default' => [
                'description' => 'Successful operation'
            ]
        ]
    )]
    public function createUser(Request $request): Response
    {
        // Create a new user based on the provided request body
        // ...

        return new Response(null, Response::HTTP_OK);
    }
}

<?php

namespace App\Controller\APP;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    //Page de maintenance quand on arrive sur le site
    #[Route('/', name: 'app_maintenance')]
    public function maintenance(): Response
    {
        // Redirect to another route
        return $this->redirectToRoute('app_login');
    }

    //
    #[Route('/app/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}

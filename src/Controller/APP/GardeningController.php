<?php

namespace App\Controller\APP;

use App\Entity\Gardening;
use App\Form\GardeningType;
use App\Repository\GardeningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gardening')]
class GardeningController extends AbstractController
{
    #[Route('/', name: 'app_gardening_index', methods: ['GET'])]
    public function index(GardeningRepository $gardeningRepository): Response
    {
        return $this->render('gardening/index.html.twig', [
            'gardenings' => $gardeningRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gardening_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GardeningRepository $gardeningRepository): Response
    {
        $gardening = new Gardening();
        $form = $this->createForm(GardeningType::class, $gardening);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gardeningRepository->save($gardening, true);

            return $this->redirectToRoute('app_gardening_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gardening/new.html.twig', [
            'gardening' => $gardening,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gardening_show', methods: ['GET'])]
    public function show(Gardening $gardening): Response
    {
        return $this->render('gardening/show.html.twig', [
            'gardening' => $gardening,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gardening_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gardening $gardening, GardeningRepository $gardeningRepository): Response
    {
        $form = $this->createForm(GardeningType::class, $gardening);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gardeningRepository->save($gardening, true);

            return $this->redirectToRoute('app_gardening_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gardening/edit.html.twig', [
            'gardening' => $gardening,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gardening_delete', methods: ['POST'])]
    public function delete(Request $request, Gardening $gardening, GardeningRepository $gardeningRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gardening->getId(), $request->request->get('_token'))) {
            $gardeningRepository->remove($gardening, true);
        }

        return $this->redirectToRoute('app_gardening_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller\APP;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Botanist;
use App\Form\Botanist1Type;
use App\Repository\BotanistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/botanist')]
class BotanistController extends AbstractController
{
    #[Route('/', name: 'app_botanist_index', methods: ['GET'])]
    public function index(BotanistRepository $botanistRepository): Response
    {
        return $this->render('botanist/index.html.twig', [
            'botanists' => $botanistRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_botanist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BotanistRepository $botanistRepository): Response
    {
        $botanist = new Botanist();
        $form = $this->createForm(Botanist1Type::class, $botanist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $botanistRepository->save($botanist, true);

            return $this->redirectToRoute('app_botanist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('botanist/new.html.twig', [
            'botanist' => $botanist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_botanist_show', methods: ['GET'])]
    public function show(Botanist $botanist): Response
    {
        return $this->render('botanist/show.html.twig', [
            'botanist' => $botanist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_botanist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Botanist $botanist, BotanistRepository $botanistRepository): Response
    {
        $form = $this->createForm(Botanist1Type::class, $botanist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $botanistRepository->save($botanist, true);

            return $this->redirectToRoute('app_botanist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('botanist/edit.html.twig', [
            'botanist' => $botanist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_botanist_delete', methods: ['POST'])]
    public function delete(Request $request, Botanist $botanist, BotanistRepository $botanistRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$botanist->getId(), $request->request->get('_token'))) {
            $botanistRepository->remove($botanist, true);
        }

        return $this->redirectToRoute('app_botanist_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php
// src/Controller/SuiviController.php

namespace App\Controller;

use App\Entity\Suivi;
use App\Form\SuiviType;
use App\Repository\SuiviRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/suivi')]
final class SuiviController extends AbstractController
{
    #[Route(name: 'app_suivi_index', methods: ['GET'])]
    public function index(SuiviRepository $suiviRepository): Response
    {
        return $this->render('suivi/index.html.twig', [
            'suivis' => $suiviRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_suivi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suivi = new Suivi();
        $form = $this->createForm(SuiviType::class, $suivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suivi);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi/new.html.twig', [
            'suivi' => $suivi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_show', methods: ['GET'])]
    public function show(Suivi $suivi): Response
    {
        return $this->render('suivi/show.html.twig', [
            'suivi' => $suivi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_suivi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Suivi $suivi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviType::class, $suivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi/edit.html.twig', [
            'suivi' => $suivi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_delete', methods: ['POST'])]
    public function delete(Request $request, Suivi $suivi, EntityManagerInterface $entityManager): Response
    {
        // Corrected CSRF token validation method
        if ($this->isCsrfTokenValid('delete'.$suivi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suivi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_index', [], Response::HTTP_SEE_OTHER);
    }
}

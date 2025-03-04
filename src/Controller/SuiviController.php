<?php

namespace App\Controller;

use App\Entity\Suivi;
use App\Form\SuiviType;
use App\Repository\SuiviRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SuiviController extends AbstractController
{
  
    #[Route('/suivi/', name: 'app_suivi_index', methods: ['GET'])]
public function index(string $context, SuiviRepository $suiviRepository, SessionInterface $session): Response
{
    if ($context === 'front') {
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('login');
        }

        $suivis = $suiviRepository->findBy(['id_user' => $userId]);
    } else {
        $suivis = $suiviRepository->findAll();
    }

    return $this->render("$context" . "frontOfficeAnimal/suivi/index.html.twig", [
        'suivis' => $suivis,
    ]);
}

    #[Route('/suivi/new', name: 'app_suivi_new', methods: ['GET', 'POST'])]
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

        return $this->render('frontOfficeAnimal/suivi/new.html.twig', [
            'suivi' => $suivi,
            'form' => $form,
        ]);
    }

    #[Route('/suivi/{id}', name: 'app_suivi_show', methods: ['GET'])]
    public function show(Suivi $suivi): Response
    {
        return $this->render('frontOfficeAnimal/suivi/show.html.twig', [
            'suivi' => $suivi,
        ]);
    }

    #[Route('/suivi/{id}/edit', name: 'app_suivi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Suivi $suivi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviType::class, $suivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontOfficeAnimal/suivi/edit.html.twig', [
            'suivi' => $suivi,
            'form' => $form,
        ]);
    }

    #[Route('/suivi/{id}', name: 'app_suivi_delete', methods: ['POST'])]
    public function delete(Request $request, Suivi $suivi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suivi->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($suivi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_index', [], Response::HTTP_SEE_OTHER);
    }



    // New routes for suivi2 directory
    #[Route('/suivi2/',name: 'app_suivi2_index', methods: ['GET'])]
    public function index2(SuiviRepository $suiviRepository): Response
    {
        return $this->render('backOfficeAnimal/suivi2/index.html.twig', [
            'suivis' => $suiviRepository->findAll(),
        ]);
    }

    #[Route('/suivi2/new', name: 'app_suivi2_new', methods: ['GET', 'POST'])]
    public function new2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suivi = new Suivi();
        $form = $this->createForm(SuiviType::class, $suivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suivi);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backOfficeAnimal/suivi2/new.html.twig', [
            'suivi' => $suivi,
            'form' => $form,
        ]);
    }

    #[Route('/suivi2/{id}', name: 'app_suivi2_show', methods: ['GET'])]
    public function show2(Suivi $suivi): Response
    {
        return $this->render('backOfficeAnimal/suivi2/show.html.twig', [
            'suivi' => $suivi,
        ]);
    }

    #[Route('/suivi2/{id}/edit', name: 'app_suivi2_edit', methods: ['GET', 'POST'])]
    public function edit2(Request $request, Suivi $suivi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviType::class, $suivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backOfficeAnimal/suivi2/edit.html.twig', [
            'suivi' => $suivi,
            'form' => $form,
        ]);
    }

    #[Route('/suivi2/{id}', name: 'app_suivi2_delete', methods: ['POST'])]
    public function delete2(Request $request, Suivi $suivi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suivi->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($suivi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi2_index', [], Response::HTTP_SEE_OTHER);
    }
}

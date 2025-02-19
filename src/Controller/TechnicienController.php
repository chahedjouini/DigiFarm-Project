<?php

namespace App\Controller;

use App\Entity\Technicien;
use App\Form\TechnicienType;
use App\Repository\TechnicienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TechnicienController extends AbstractController
{
    #[Route('/technicien/front_machine', name: 'front_machine_page')]
    public function index2(): Response
    {
        return $this->render('frontOfficeMachine/frontMachine.html.twig');
    }

    #[Route('/technicien', name: 'app_technicien_index', methods: ['GET'])]
    public function index(TechnicienRepository $technicienRepository): Response
    {
        return $this->render('frontOfficeMachine/technicien/index.html.twig', [
            'techniciens' => $technicienRepository->findAll(),
        ]);
    }

    #[Route('/technicien/new', name: 'app_technicien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $technicien = new Technicien();
        $form = $this->createForm(TechnicienType::class, $technicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($technicien);
            $entityManager->flush();

            return $this->redirectToRoute('app_technicien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontOfficeMachine/technicien/new.html.twig', [
            'technicien' => $technicien,
            'form' => $form,
        ]);
    }

    #[Route('/technicien/{id}', name: 'app_technicien_show', methods: ['GET'])]
    public function show(Technicien $technicien): Response
    {
        return $this->render('frontOfficeMachine/technicien/show.html.twig', [
            'technicien' => $technicien,
        ]);
    }

    #[Route('/technicien/{id}/edit', name: 'app_technicien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Technicien $technicien, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TechnicienType::class, $technicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_technicien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontOfficeMachine/technicien/edit.html.twig', [
            'technicien' => $technicien,
            'form' => $form,
        ]);
    }

    #[Route('/technicien/{id}', name: 'app_technicien_delete', methods: ['POST'])]
    public function delete(Request $request, Technicien $technicien, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $technicien->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($technicien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_technicien_index', [], Response::HTTP_SEE_OTHER);
    }

    // 🔹 Technicien 2 (avec noms de routes corrigés)

    #[Route('/technicien2', name: 'app_technicien2_index', methods: ['GET'])]
    public function indexTechnicien2(TechnicienRepository $technicienRepository): Response
    {
        return $this->render('backOfficeMachine/technicien2/index.html.twig', [
            'techniciens' => $technicienRepository->findAll(),
        ]);
    }

    #[Route('/technicien2/new', name: 'app_technicien2_new', methods: ['GET', 'POST'])]
    public function newTechnicien2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $technicien = new Technicien();
        $form = $this->createForm(TechnicienType::class, $technicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($technicien);
            $entityManager->flush();

            return $this->redirectToRoute('app_technicien2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backOfficeMachine/technicien2/new.html.twig', [
            'technicien' => $technicien,
            'form' => $form,
        ]);
    }

    #[Route('/technicien2/{id}', name: 'app_technicien2_show', methods: ['GET'])]
    public function showTechnicien2(Technicien $technicien): Response
    {
        return $this->render('backOfficeMachine/technicien2/show.html.twig', [
            'technicien' => $technicien,
        ]);
    }

    #[Route('/technicien2/{id}/edit', name: 'app_technicien2_edit', methods: ['GET', 'POST'])]
    public function editTechnicien2(Request $request, Technicien $technicien, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TechnicienType::class, $technicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_technicien2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backOfficeMachine/technicien2/edit.html.twig', [
            'technicien' => $technicien,
            'form' => $form,
        ]);
    }

    #[Route('/technicien2/{id}', name: 'app_technicien2_delete', methods: ['POST'])]
    public function deleteTechnicien2(Request $request, Technicien $technicien, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $technicien->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($technicien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_technicien2_delete', [], Response::HTTP_SEE_OTHER);
    }
}

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

#[Route('/technicien')]
final class TechnicienController extends AbstractController
{
    #[Route(name: 'app_technicien_index', methods: ['GET'])]
    public function index(TechnicienRepository $technicienRepository): Response
    {
        return $this->render('technicien/index.html.twig', [
            'techniciens' => $technicienRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_technicien_new', methods: ['GET', 'POST'])]
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

        return $this->render('technicien/new.html.twig', [
            'technicien' => $technicien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_technicien_show', methods: ['GET'])]
    public function show(Technicien $technicien): Response
    {
        return $this->render('technicien/show.html.twig', [
            'technicien' => $technicien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_technicien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Technicien $technicien, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TechnicienType::class, $technicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_technicien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('technicien/edit.html.twig', [
            'technicien' => $technicien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_technicien_delete', methods: ['POST'])]
    public function delete(Request $request, Technicien $technicien, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$technicien->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($technicien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_technicien_index', [], Response::HTTP_SEE_OTHER);
    }
}

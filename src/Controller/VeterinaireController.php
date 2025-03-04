<?php

namespace App\Controller;

use App\Entity\Veterinaire;
use App\Form\VeterinaireType;
use App\Repository\VeterinaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VeterinaireController extends AbstractController
{
    #[Route('/veterinaire/',name: 'app_veterinaire_index', methods: ['GET'])]
    public function index(VeterinaireRepository $veterinaireRepository): Response
    {
        return $this->render('frontOfficeAnimal/veterinaire/index.html.twig', [
            'veterinaires' => $veterinaireRepository->findAll(),
        ]);
    }

    #[Route('/veterinaire/new', name: 'app_veterinaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $veterinaire = new Veterinaire();
        $form = $this->createForm(VeterinaireType::class, $veterinaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($veterinaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_veterinaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontOfficeAnimal/veterinaire/new.html.twig', [
            'veterinaire' => $veterinaire,
            'form' => $form,
        ]);
    }

    #[Route('/veterinaire/{id}', name: 'app_veterinaire_show', methods: ['GET'])]
    public function show(Veterinaire $veterinaire): Response
    {
        return $this->render('frontOfficeAnimal/veterinaire/show.html.twig', [
            'veterinaire' => $veterinaire,
        ]);
    }

    #[Route('/veterinaire/{id}/edit', name: 'app_veterinaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Veterinaire $veterinaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VeterinaireType::class, $veterinaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_veterinaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontOfficeAnimal/veterinaire/edit.html.twig', [
            'veterinaire' => $veterinaire,
            'form' => $form,
        ]);
    }

    #[Route('/veterinaire/{id}', name: 'app_veterinaire_delete', methods: ['POST'])]
    public function delete(Request $request, Veterinaire $veterinaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$veterinaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($veterinaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_veterinaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/veterinaire2/',name: 'app_veterinaire2_index', methods: ['GET'])]
    public function index2(VeterinaireRepository $veterinaireRepository): Response
    {
        return $this->render('backOfficeAnimal/veterinaire2/index.html.twig', [
            'veterinaires' => $veterinaireRepository->findAll(),
        ]);
    }

    #[Route('/veterinaire2/new', name: 'app_veterinaire2_new', methods: ['GET', 'POST'])]
    public function new2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $veterinaire = new Veterinaire();
        $form = $this->createForm(VeterinaireType::class, $veterinaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($veterinaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_veterinaire2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backOfficeAnimal/veterinaire2/new.html.twig', [
            'veterinaire' => $veterinaire,
            'form' => $form,
        ]);
    }

    #[Route('/veterinaire2/{id}', name: 'app_veterinaire2_show', methods: ['GET'])]
    public function show2(Veterinaire $veterinaire): Response
    {
        return $this->render('backOfficeAnimal/veterinaire2/show.html.twig', [
            'veterinaire' => $veterinaire,
        ]);
    }

    #[Route('/veterinaire2/{id}/edit', name: 'app_veterinaire2_edit', methods: ['GET', 'POST'])]
    public function edit2(Request $request, Veterinaire $veterinaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VeterinaireType::class, $veterinaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_veterinaire2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backOfficeAnimal/veterinaire2/edit.html.twig', [
            'veterinaire' => $veterinaire,
            'form' => $form,
        ]);
    }

    #[Route('/veterinaire2/{id}', name: 'app_veterinaire2_delete', methods: ['POST'])]
    public function delete2(Request $request, Veterinaire $veterinaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$veterinaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($veterinaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_veterinaire2_index', [], Response::HTTP_SEE_OTHER);
    }

}

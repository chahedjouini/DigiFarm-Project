<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\Animal1Type;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AnimalController extends AbstractController
{
    #[Route('/animal/front_animal', name: 'front_animal_page')]
    public function index3(): Response
    {
        return $this->render('frontOfficeAnimal/frontAnimal.html.twig');
    }
    #[Route('/animal/',name: 'app_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository): Response
    {
        return $this->render('frontOfficeAnimal/animal/index.html.twig', [
            'animals' => $animalRepository->findAll(),
        ]);
    }

    #[Route('/animal/new', name: 'app_animal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $animal = new Animal();
        $form = $this->createForm(Animal1Type::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($animal);
            $entityManager->flush();

            return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontOfficeAnimal/animal/new.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    

    #[Route('/animal/{id}', name: 'app_animal_show', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return $this->render('frontOfficeAnimal/animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    #[Route('/animal/{id}/edit', name: 'app_animal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Animal1Type::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontOfficeAnimal/animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/animal/{id}', name: 'app_animal_delete', methods: ['POST'])]
    public function delete(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
    }



// New routes for animal2 directory

    #[Route('/animal2/',name: 'app_animal2_index', methods: ['GET'])]
    public function index2(AnimalRepository $animalRepository): Response
    {
        return $this->render('backOfficeAnimal/animal2/index.html.twig', [
            'animals' => $animalRepository->findAll(),
        ]);
    }

    #[Route('/animal2/new', name: 'app_animal2_new', methods: ['GET', 'POST'])]
    public function new2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $animal = new Animal();
        $form = $this->createForm(Animal1Type::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($animal);
            $entityManager->flush();

            return $this->redirectToRoute('app_animal2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backOfficeAnimal/animal2/new.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    

    #[Route('/animal2/{id}', name: 'app_animal2_show', methods: ['GET'])]
    public function show2(Animal $animal): Response
    {
        return $this->render('backOfficeAnimal/animal2/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    #[Route('/animal2/{id}/edit', name: 'app_animal2_edit', methods: ['GET', 'POST'])]
    public function edit2(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Animal1Type::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_animal2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backOfficeAnimal/animal2/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/animal2/{id}', name: 'app_animal2_delete', methods: ['POST'])]
    public function delete2(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_animal2_index', [], Response::HTTP_SEE_OTHER);
    }
}

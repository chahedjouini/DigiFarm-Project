<?php

namespace App\Controller;

use App\Entity\Culture;
use App\Form\CultureType;
use App\Repository\CultureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/culture/{context}', requirements: ['context' => 'front|back'])]
final class CultureController extends AbstractController
{
    #[Route('/', name: 'app_culture_index', methods: ['GET'])]
    public function index(string $context, CultureRepository $cultureRepository): Response
    {
        return $this->render("$context" . "OfficeEtude/culture/index.html.twig", [
            'cultures' => $cultureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_culture_new', methods: ['GET', 'POST'])]
    public function new(string $context, Request $request, EntityManagerInterface $entityManager): Response
    {
        $culture = new Culture();
        $form = $this->createForm(CultureType::class, $culture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($culture);
            $entityManager->flush();

            return $this->redirectToRoute('app_culture_index', ['context' => $context], Response::HTTP_SEE_OTHER);
        }

        return $this->render("$context" . "OfficeEtude/culture/new.html.twig", [
            'culture' => $culture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_culture_show', methods: ['GET'])]
    public function show(string $context, Culture $culture): Response
    {
        return $this->render("$context" . "OfficeEtude/culture/show.html.twig", [
            'culture' => $culture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_culture_edit', methods: ['GET', 'POST'])]
    public function edit(string $context, Request $request, Culture $culture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CultureType::class, $culture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_culture_index', ['context' => $context], Response::HTTP_SEE_OTHER);
        }

        return $this->render("$context" . "OfficeEtude/culture/edit.html.twig", [
            'culture' => $culture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_culture_delete', methods: ['POST'])]
    public function delete(string $context, Request $request, Culture $culture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $culture->getId(), $request->get('_token'))) {
            $entityManager->remove($culture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_culture_index', ['context' => $context], Response::HTTP_SEE_OTHER);
    }
}

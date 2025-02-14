<?php

namespace App\Controller;

use App\Entity\Expert;
use App\Form\ExpertType;
use App\Repository\ExpertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/expert/{context}', requirements: ['context' => 'front|back'])]
final class ExpertController extends AbstractController
{
    #[Route('/', name: 'app_expert_index', methods: ['GET'])]
    public function index(string $context, ExpertRepository $expertRepository): Response
    {
        return $this->render("$context" . "OfficeEtude/expert/index.html.twig", [
            'experts' => $expertRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_expert_new', methods: ['GET', 'POST'])]
    public function new(string $context, Request $request, EntityManagerInterface $entityManager): Response
    {
        $expert = new Expert();
        $form = $this->createForm(ExpertType::class, $expert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($expert);
            $entityManager->flush();

            return $this->redirectToRoute('app_expert_index', ['context' => $context], Response::HTTP_SEE_OTHER);
        }

        return $this->render("$context" . "OfficeEtude/expert/new.html.twig", [
            'expert' => $expert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expert_show', methods: ['GET'])]
    public function show(string $context, Expert $expert): Response
    {
        return $this->render("$context" . "OfficeEtude/expert/show.html.twig", [
            'expert' => $expert,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_expert_edit', methods: ['GET', 'POST'])]
    public function edit(string $context, Request $request, Expert $expert, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExpertType::class, $expert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_expert_index', ['context' => $context], Response::HTTP_SEE_OTHER);
        }

        return $this->render("$context" . "OfficeEtude/expert/edit.html.twig", [
            'expert' => $expert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expert_delete', methods: ['POST'])]
    public function delete(string $context, Request $request, Expert $expert, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $expert->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($expert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_expert_index', ['context' => $context], Response::HTTP_SEE_OTHER);
    }
}

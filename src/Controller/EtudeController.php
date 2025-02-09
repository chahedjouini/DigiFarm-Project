<?php

namespace App\Controller;

use App\Entity\Etude;
use App\Form\EtudeType;
use App\Repository\EtudeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/etude')]
final class EtudeController extends AbstractController
{
    #[Route(name: 'app_etude_index', methods: ['GET'])]
    public function index(EtudeRepository $etudeRepository): Response
    {
        return $this->render('etude/index.html.twig', [
            'etudes' => $etudeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_etude_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etude = new Etude();
        $form = $this->createForm(EtudeType::class, $etude);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etude);
            $entityManager->flush();

            return $this->redirectToRoute('app_etude_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etude/new.html.twig', [
            'etude' => $etude,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etude_show', methods: ['GET'])]
    public function show(Etude $etude): Response
    {
        return $this->render('etude/show.html.twig', [
            'etude' => $etude,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etude_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etude $etude, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtudeType::class, $etude);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_etude_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etude/edit.html.twig', [
            'etude' => $etude,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etude_delete', methods: ['POST'])]
    public function delete(Request $request, Etude $etude, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etude->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($etude);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_etude_index', [], Response::HTTP_SEE_OTHER);
    }
}

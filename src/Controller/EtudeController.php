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
use App\Enum\Dispo;

#[Route('/etude/{context}', requirements: ['context' => 'front|back'])]
final class EtudeController extends AbstractController
{
    #[Route('/', name: 'app_etude_index', methods: ['GET'])]
    public function index(string $context, EtudeRepository $etudeRepository): Response
    {
        return $this->render("$context" . "OfficeEtude/etude/index.html.twig", [
            'etudes' => $etudeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_etude_new', methods: ['GET', 'POST'])]
    public function new(string $context, Request $request, EntityManagerInterface $entityManager): Response
    {
        $etude = new Etude();
        $form = $this->createForm(EtudeType::class, $etude);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $expert = $etude->getExpert();
            
            // Si un expert est sélectionné, on le marque comme non disponible
            if ($expert) {
                $expert->setDispo(Dispo::NON_DISPONIBLE);
                $entityManager->flush();  // Sauvegarder les changements
            }
    
            $entityManager->persist($etude);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_etude_index', ['context' => $context], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render("$context" . "OfficeEtude/etude/new.html.twig", [
            'etude' => $etude,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_etude_show', methods: ['GET'])]
    public function show(string $context, Etude $etude): Response
    {
        return $this->render("$context" . "OfficeEtude/etude/show.html.twig", [
            'etude' => $etude,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etude_edit', methods: ['GET', 'POST'])]
public function edit(string $context, Request $request, Etude $etude, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(EtudeType::class, $etude);
    $form->handleRequest($request);

    $originalExpert = $etude->getExpert();  // Garder l'expert initial avant la modification

    if ($form->isSubmitted() && $form->isValid()) {
        // Si l'expert a changé, mettre à jour la disponibilité
        $newExpert = $etude->getExpert();

        if ($originalExpert !== $newExpert) {
            // Si l'expert initial est différent du nouvel expert, remettre l'ancien expert à "disponible"
            $originalExpert->setDispo(Dispo::DISPONIBLE);
            $entityManager->flush(); // Sauvegarder la remise à disponible de l'ancien expert

            // Et marquer le nouvel expert comme "non disponible"
            if ($newExpert) {
                $newExpert->setDispo(Dispo::NON_DISPONIBLE);
                $entityManager->flush();  // Sauvegarder la mise à jour
            }
        }

        $entityManager->flush(); // Sauvegarder les modifications de l'étude

        return $this->redirectToRoute('app_etude_index', ['context' => $context], Response::HTTP_SEE_OTHER);
    }

    return $this->render("$context" . "OfficeEtude/etude/edit.html.twig", [
        'etude' => $etude,
        'form' => $form,
    ]);
}


#[Route('/{id}', name: 'app_etude_delete', methods: ['POST'])]
public function delete(string $context, Request $request, Etude $etude, EntityManagerInterface $entityManager): Response
{
    // Vérifier la validité du token CSRF
    if ($this->isCsrfTokenValid('delete' . $etude->getId(), $request->get('_token'))) {
        
        // Récupérer l'expert associé à l'étude
        $expert = $etude->getExpert();

        // Si un expert est associé à l'étude, on le remet à "disponible"
        if ($expert) {
            $expert->setDispo(Dispo::DISPONIBLE);
            $entityManager->flush(); // Sauvegarder la mise à jour de la disponibilité de l'expert
        }

        // Supprimer l'étude de la base de données
        $entityManager->remove($etude);
        $entityManager->flush(); // Sauvegarder la suppression de l'étude
    }

    // Rediriger vers la page d'index des études
    return $this->redirectToRoute('app_etude_index', ['context' => $context], Response::HTTP_SEE_OTHER);
}

}

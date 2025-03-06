<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Etude;
use App\Form\EtudeType;
use App\Repository\EtudeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Enum\Dispo;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/etude/{context}', requirements: ['context' => 'front|back'])]
final class EtudeController extends AbstractController
{
    

    #[Route('/', name: 'app_etude_index', methods: ['GET'])]
    public function index(string $context, EtudeRepository $etudeRepository, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId) {
                return $this->redirectToRoute('login');
            }

            $etudes = $etudeRepository->findBy(['id_user' => $userId]);
        } else {
            $etudes = $etudeRepository->findAll();
        }

        return $this->render("$context" . "OfficeEtude/etude/index.html.twig", [
            'etudes' => $etudes,
        ]);
    }

    #[Route('/new', name: 'app_etude_new', methods: ['GET', 'POST'])]
    public function new(string $context, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId) {
                return $this->redirectToRoute('login');
            }
        }

        $etude = new Etude();
        $form = $this->createForm(EtudeType::class, $etude);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($context === 'front') {
                $user = $entityManager->getRepository(User::class)->find($session->get('user_id'));
                if (!$user) {
                    throw $this->createNotFoundException('User not found');
                }
                $etude->setIdUser($user);
            }

            $expert = $etude->getExpert();
            if ($expert) {
                $expert->setDispo(Dispo::NON_DISPONIBLE);
                $entityManager->flush();
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
    public function show(string $context, Etude $etude, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId || $etude->getIdUser()->getId() !== $userId) {
                return $this->redirectToRoute('login');
            }
        }

        return $this->render("$context" . "OfficeEtude/etude/show.html.twig", [
            'etude' => $etude,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etude_edit', methods: ['GET', 'POST'])]
    public function edit(string $context, Request $request, Etude $etude, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId || $etude->getIdUser()->getId() !== $userId) {
                return $this->redirectToRoute('login');
            }
        }

        $form = $this->createForm(EtudeType::class, $etude);
        $form->handleRequest($request);

        $originalExpert = $etude->getExpert();

        if ($form->isSubmitted() && $form->isValid()) {
            $newExpert = $etude->getExpert();
            if ($originalExpert !== $newExpert) {
                $originalExpert->setDispo(Dispo::DISPONIBLE);
                $entityManager->flush();
                if ($newExpert) {
                    $newExpert->setDispo(Dispo::NON_DISPONIBLE);
                    $entityManager->flush();
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_etude_index', ['context' => $context], Response::HTTP_SEE_OTHER);
        }

        return $this->render("$context" . "OfficeEtude/etude/edit.html.twig", [
            'etude' => $etude,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etude_delete', methods: ['POST'])]
    public function delete(string $context, Request $request, Etude $etude, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId || $etude->getIdUser()->getId() !== $userId) {
                return $this->redirectToRoute('login');
            }
        }

        if ($this->isCsrfTokenValid('delete' . $etude->getId(), $request->get('_token'))) {
            $expert = $etude->getExpert();
            if ($expert) {
                $expert->setDispo(Dispo::DISPONIBLE);
                $entityManager->flush();
            }
            $entityManager->remove($etude);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_etude_index', ['context' => $context], Response::HTTP_SEE_OTHER);
    }
}

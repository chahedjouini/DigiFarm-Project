<?php
// src/Controller/CultureController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Culture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\CultureType;
use App\Service\PhpMlService;  // Correct the namespace here

use App\Repository\CultureRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/culture/{context}', requirements: ['context' => 'front|back'])]
final class CultureController extends AbstractController
{
    private PhpMlService $phpMlService;

    public function __construct(PhpMlService $phpMlService)
    {
        $this->phpMlService = $phpMlService;
    }

      // Predict rendement
      #[Route("/predict-rendement", name: "predict_rendement", methods: ["POST"])]
      public function predictRendement(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Decode the incoming request data (culture.id)
        $data = json_decode($request->getContent(), true);
        $cultureId = $data['cultureId'];

        // Fetch the Culture entity by id
        $culture = $entityManager->getRepository(Culture::class)->find($cultureId);

        if (!$culture) {
            return new JsonResponse(['error' => 'Culture not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Now you have access to the Culture entity, you can use the attributes for the prediction
        $etude = $culture->getEtudes()->first(); // Assuming one Etude per Culture

        if (!$etude) {
            return new JsonResponse(['error' => 'No Etude found for this culture'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Prepare data for prediction
        $newData = [
            $etude->getTemperature(),
            $etude->getPrecipitations(),
            $etude->getDensitePlantation(),
            $etude->getFacteurInfluence()
        ];

        // Use PhpMlService to predict the rendement (yield)
        $predictedRendement = $this->phpMlService->predict($newData);

        // Return the predicted rendement as JSON response
        return new JsonResponse(['predictedRendement' => $predictedRendement]);
    }

    #[Route('/', name: 'app_culture_index', methods: ['GET'])]
    public function index(string $context, CultureRepository $cultureRepository, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId) {
                return $this->redirectToRoute('login');
            }

            $cultures = $cultureRepository->findBy(['id_user' => $userId]);
        } else {
            $cultures = $cultureRepository->findAll();
        }

        return $this->render("$context" . "OfficeEtude/culture/index.html.twig", [
            'cultures' => $cultures,
        ]);
    }

    #[Route('/new', name: 'app_culture_new', methods: ['GET', 'POST'])]
    public function new(string $context, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId) {
                return $this->redirectToRoute('login');
            }
        }

        $culture = new Culture();
        $form = $this->createForm(CultureType::class, $culture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($context === 'front') {
                $user = $entityManager->getRepository(User::class)->find($session->get('user_id'));
                if (!$user) {
                    throw $this->createNotFoundException('User not found');
                }
                $culture->setIdUser($user);
            }

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
    public function show(string $context, Culture $culture, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId || $culture->getIdUser()->getId() !== $userId) {
                return $this->redirectToRoute('login');
            }
        }

        return $this->render("$context" . "OfficeEtude/culture/show.html.twig", [
            'culture' => $culture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_culture_edit', methods: ['GET', 'POST'])]
    public function edit(string $context, Request $request, Culture $culture, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId || $culture->getIdUser()->getId() !== $userId) {
                return $this->redirectToRoute('login');
            }
        }

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
    public function delete(string $context, Request $request, Culture $culture, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($context === 'front') {
            $userId = $session->get('user_id');
            if (!$userId || $culture->getIdUser()->getId() !== $userId) {
                return $this->redirectToRoute('login');
            }
        }

        if ($this->isCsrfTokenValid('delete' . $culture->getId(), $request->get('_token'))) {
            $entityManager->remove($culture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_culture_index', ['context' => $context], Response::HTTP_SEE_OTHER);
    }
}

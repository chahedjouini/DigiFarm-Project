<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Repository\MaintenanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class MaintenanceController extends AbstractController
{
    #[Route('/maintenance/',name: 'app_maintenance_index', methods: ['GET'])]
    public function index(MaintenanceRepository $maintenanceRepository): Response
    {
        return $this->render('maintenance/index.html.twig', [
            'maintenances' => $maintenanceRepository->findAll(),
        ]);
    }

    #[Route('/maintenance/new', name: 'app_maintenance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $maintenance = new Maintenance();
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($maintenance);
            $entityManager->flush();

            return $this->redirectToRoute('app_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maintenance/new.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,
        ]);
    }

    #[Route('maintenance/{id}', name: 'app_maintenance_show', methods: ['GET'])]
    public function show(Maintenance $maintenance): Response
    {
        return $this->render('maintenance/show.html.twig', [
            'maintenance' => $maintenance,
        ]);
    }

    #[Route('/maintenance/{id}/edit', name: 'app_maintenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Maintenance $maintenance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maintenance/edit.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,
        ]);
    }

    #[Route('/maintenance/{id}', name: 'app_maintenance_delete', methods: ['POST'])]
    public function delete(Request $request, Maintenance $maintenance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maintenance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($maintenance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_maintenance_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/maintenance2', name: 'app_maintenance2_index', methods: ['GET'])]
    public function indexMaintenance2(MaintenanceRepository $maintenanceRepository): Response
    {
        return $this->render('maintenance2/index.html.twig', [
            'maintenances' => $maintenanceRepository->findAll(),
        ]);
    }

    #[Route('/maintenance2/new', name: 'app_maintenance2_new', methods: ['GET', 'POST'])]
    public function newMaintenance2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $maintenance = new Maintenance();
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($maintenance);
            $entityManager->flush();

            return $this->redirectToRoute('app_maintenance2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maintenance2/new.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,
        ]);
    }

    #[Route('/maintenance2/{id}', name: 'app_maintenance2_show', methods: ['GET'])]
    public function showMaintenance2(Maintenance $maintenance): Response
    {
        return $this->render('maintenance2/show.html.twig', [
            'maintenance' => $maintenance,
        ]);
    }

    #[Route('/maintenance2/{id}/edit', name: 'app_maintenance2_edit', methods: ['GET', 'POST'])]
    public function editMaintenance2(Request $request, Maintenance $maintenance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_maintenance2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('maintenance2/edit.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,
        ]);
    }

    #[Route('/maintenance2/{id}', name: 'app_maintenance2_delete', methods: ['POST'])]
    public function deleteMaintenance2(Request $request, Maintenance $maintenance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maintenance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maintenance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_maintenance2_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Form\MachineType;// creating/editing 
use App\Repository\MachineRepository;//querying
use Doctrine\ORM\EntityManagerInterface;//managing database operations.
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;//helper methods for rendering templates
use Symfony\Component\HttpFoundation\Request;//HTTP requests and responses.
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;//defining routes
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

final class MachineController extends AbstractController
{    #[Route('/machine/front_machine', name: 'front_machine_page')]
    public function index2(): Response
    {
        return $this->render('frontMachine.html.twig');
    }
    #[Route('/contact_machine', name: 'contact_machine_page')]
    public function index3(): Response
    {
        return $this->render('contact.html.twig');
    }
    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }


    #[Route('/machine/', name: 'app_machine_index', methods: ['GET'])]//only get request
    #[IsGranted('ROLE_CLIENT')]
    public function index(MachineRepository $machineRepository): Response//ll machines from the database using using repisotory
    {
        return $this->render('machine/index.html.twig', [
            'machines' => $machineRepository->findAll(),
        ]);
    }

    #[Route('/machine/new', name: 'app_machine_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_AGRICULTEUR')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $machine = new Machine();
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($machine);
            $entityManager->flush();

            return $this->redirectToRoute('app_machine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machine/new.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }

    #[Route('/machine/{id}', name: 'app_machine_show', methods: ['GET'])]
    #[IsGranted('ROLE_AGRICULTEUR')]
    public function show(Machine $machine): Response
    {
        return $this->render('machine/show.html.twig', [
            'machine' => $machine,
        ]);
    }

    #[Route('/machine/{id}/edit', name: 'app_machine_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_AGRICULTEUR')]
    public function edit(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_machine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machine/edit.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }

    #[Route('/machine/{id}', name: 'app_machine_delete', methods: ['POST'])]
    #[IsGranted('ROLE_AGRICULTEUR')]
    public function delete(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($machine);
        $entityManager->flush();

        return $this->redirectToRoute('app_machine_index', [], Response::HTTP_SEE_OTHER);
    }

    // New routes for machine2 directory
    #[Route('/machine2', name: 'app_machine2_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function indexMachine2(MachineRepository $machineRepository): Response
    {
        return $this->render('machine2/index.html.twig', [
            'machines' => $machineRepository->findAll(),
        ]);
    }

    #[Route('/machine2/new', name: 'app_machine2_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function newMachine2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $machine = new Machine();
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($machine);
            $entityManager->flush();

            return $this->redirectToRoute('app_machine2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machine2/new.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }

    #[Route('/machine2/{id}', name: 'app_machine2_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function showMachine2(Machine $machine): Response
    {
        return $this->render('machine2/show.html.twig', [
            'machine' => $machine,
        ]);
    }

    #[Route('/machine2/{id}/edit', name: 'app_machine2_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editMachine2(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_machine2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machine2/edit.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }

    #[Route('/machine2/{id}', name: 'app_machine2_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteMachine2(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($machine);
        $entityManager->flush();

        return $this->redirectToRoute('app_machine2_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Enum\UserRole;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class UserController extends AbstractController
{
    #[Route(path: '/userback', name: 'app_user_index', methods: ['GET'])]
public function index(SessionInterface $session, UserRepository $userRepository): Response
{
    // Vérifier si l'utilisateur est connecté
    if (!$session->has('user_id')) {
        // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        return $this->redirectToRoute('login');
    }

    // Ajouter des en-têtes pour éviter le cache dans le navigateur
    $response = $this->render('backOfficeUser/index.html.twig', [
        'users' => $userRepository->findAll(),
    ]);

    // Définir les en-têtes HTTP pour interdire la mise en cache côté client
    $response->setCache([
        'no_store' => true,
        'no_cache' => true,
        'must_revalidate' => true,
        'max_age' => 0
    ]);

    return $response;
}



    #[Route(path: 'userback/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérification de l'unicité de l'email
        $existingUserByEmail = $entityManager->getRepository(User::class)->findOneBy(['AdresseMail' => $user->getAdresseMail()]);
        if ($existingUserByEmail) {
            $this->addFlash('error', 'Cet email est déjà utilisé.');
            return $this->redirectToRoute('app_user_new');
        }

        // Vérification de l'unicité du mot de passe
        // Cette vérification est rare, mais ajoutée ici pour votre besoin.
        $existingUserByPassword = $entityManager->getRepository(User::class)->findOneBy(['Password' => $user->getPassword()]);
        if ($existingUserByPassword) {
            $this->addFlash('error', 'Ce mot de passe est déjà utilisé.');
            return $this->redirectToRoute('app_user_new');
        }

        // Sauvegarder l'utilisateur
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('backOfficeUser/new.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
    }


    #[Route('userback/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('backOfficeUser/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('userback/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
{
    // Créer le formulaire pour l'utilisateur
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    // Vérification si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Vérification de l'unicité de l'email, sauf si c'est le même utilisateur
        $existingUserByEmail = $entityManager->getRepository(User::class)->findOneBy(['AdresseMail' => $user->getAdresseMail()]);
        if ($existingUserByEmail && $existingUserByEmail->getId() !== $user->getId()) {
            $this->addFlash('error', 'Cet email est déjà utilisé.');
            return $this->render('backOfficeUser/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }

        // Vérification de l'unicité du mot de passe, sauf si c'est le même utilisateur
        $existingUserByPassword = $entityManager->getRepository(User::class)->findOneBy(['Password' => $user->getPassword()]);
        if ($existingUserByPassword && $existingUserByPassword->getId() !== $user->getId()) {
            $this->addFlash('error', 'Ce mot de passe est déjà utilisé.');
            return $this->render('backOfficeUser/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }

        // Si tout est validé, on met à jour l'utilisateur
        $entityManager->flush();

        // Redirection après l'enregistrement
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    // Rendu du formulaire d'édition
    return $this->render('backOfficeUser/edit.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);
}


    #[Route('userback/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route(path: 'userfront/signup', name: 'app_user_new_front', methods: ['GET', 'POST'])]
    public function signup(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérification de l'unicité de l'email
        $existingUserByEmail = $entityManager->getRepository(User::class)->findOneBy(['AdresseMail' => $user->getAdresseMail()]);
        if ($existingUserByEmail) {
            $this->addFlash('error', 'Cet email est déjà utilisé.');
            return $this->redirectToRoute('app_user_new');
        }

        // Vérification de l'unicité du mot de passe
        // Cette vérification est rare, mais ajoutée ici pour votre besoin.
        $existingUserByPassword = $entityManager->getRepository(User::class)->findOneBy(['Password' => $user->getPassword()]);
        if ($existingUserByPassword) {
            $this->addFlash('error', 'Ce mot de passe est déjà utilisé.');
            return $this->redirectToRoute('app_user_new');
        }

        // Sauvegarder l'utilisateur
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('home_page', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('frontOfficeUser/signup.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
    }
    
    #[Route('userfront/{id}', name: 'app_user_showmine', methods: ['GET'])]
public function showmine($id, SessionInterface $session, EntityManagerInterface $entityManager): Response
{
    // Vérifie si l'utilisateur est connecté en vérifiant la session
    $userId = $session->get('user_id');
    if (!$userId || $userId != $id) {
        // Si l'utilisateur n'est pas connecté ou l'ID ne correspond pas, rediriger vers la page de connexion
        return $this->redirectToRoute('login');
    }

    // Récupère l'utilisateur connecté depuis la base de données
    $user = $entityManager->getRepository(User::class)->find($id);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    return $this->render('frontOfficeUser/show.html.twig', [
        'user' => $user,
    ]);
}

#[Route('/userfront/delete/{id}', name: 'app_user_deletemine', methods: ['POST'])]
public function deletemine($id, SessionInterface $session, EntityManagerInterface $entityManager): Response
{
    // Vérifie si l'utilisateur est connecté et si l'ID de l'utilisateur dans la session correspond à celui passé dans l'URL
    $userId = $session->get('user_id');
    if (!$userId || $userId != $id) {
        // Si l'ID ne correspond pas ou si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        return $this->redirectToRoute('login');
    }

    // Récupérer l'utilisateur à supprimer
    $user = $entityManager->getRepository(User::class)->find($id);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    // Supprimer l'utilisateur
    $entityManager->remove($user);
    $entityManager->flush();

    // Déconnecter l'utilisateur en supprimant ses données de session
    $session->clear();

    // Rediriger vers la page d'accueil ou la page de connexion après la suppression
    return $this->redirectToRoute('home_page');
}

#[Route('/userfront/edit/{id}', name: 'app_user_editmine', methods: ['GET', 'POST'])]
public function editUser($id, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    // Vérifie si l'utilisateur est connecté et si l'ID de l'utilisateur dans la session correspond à celui passé dans l'URL
    $userId = $session->get('user_id');
    if (!$userId || $userId != $id) {
        return $this->redirectToRoute('login');
    }

    // Récupérer l'utilisateur à modifier
    $user = $entityManager->getRepository(User::class)->find($id);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    // Créer le formulaire
    $form = $this->createForm(UserType::class, $user);
    
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Mettre à jour les données de l'utilisateur
        $entityManager->flush();

        $this->addFlash('success', 'Vos informations ont été mises à jour.');
        return $this->redirectToRoute('app_user_showmine', ['id' => $user->getId()]);
    }

    return $this->render('frontOfficeUser/edit.html.twig', [
        'form' => $form->createView(),
        'userRole' => $user->getRole()->value, // Passer la valeur du rôle sous forme de chaîne
        'user' => $user, // Passer la variable utilisateur
    ]);
}





    #[Route('/login', name: 'login')]
public function login(Request $request, SessionInterface $session, EntityManagerInterface $entityManager)
{
    if ($request->isMethod('POST')) {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $entityManager->getRepository(User::class)->findOneBy(['AdresseMail' => $email]);

        if ($user && $password === $user->getPassword()) {
            // Stockage des données dans la session
            $session->set('user_id', $user->getId());
            $session->set('user_name', $user->getNom());  // Ajouter le nom
            $session->set('user_role', $user->getRole()->value);  // Ajout du rôle en texte
            
            // Redirection selon le rôle
            if ($user->getRole() === UserRole::ADMIN) {
                return $this->redirectToRoute('app_user_index'); // Admin vers le back-office
            } else {
                return $this->render('base_loggedin.html.twig'); // Autres utilisateurs vers la page d'accueil
            }
        } else {
            $this->addFlash('error', 'Email ou mot de passe incorrect.');
        }
    }

    return $this->render('frontOfficeUser/login.html.twig');
}


#[Route('/logout', name: 'logout')]
public function logout(SessionInterface $session, Request $request)
{
    // Effacer la session
    $session->clear();

    // Pour éviter le retour arrière, on effectue une redirection vers la page d'accueil
    return $this->redirectToRoute('home_page');
}


}

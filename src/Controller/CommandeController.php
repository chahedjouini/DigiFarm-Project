<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\CommandeDetail;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/commande')]
final class CommandeController extends AbstractController
{
    // //  AFFICHAGE DES COMMANDES EN MODE ADMIN
     #[Route('/list', name: 'app_commande_gestion', methods: ['GET'])]
     public function indexBack(CommandeRepository $commandeRepository): Response
     {
         return $this->render('commande/List.html.twig', [
             'commandes' => $commandeRepository->findAll(),
         ]);
     }

    
    //*** Finalisation commande */
    #[Route( name: 'commande_page', methods: ['GET', 'POST'])]
    public function commander(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les IDs des produits sélectionnés
        $produitIds = $request->request->all('produits'); // ✅ Utilisation de all() pour récupérer un tableau
    
        // Vérification si la liste est vide
        if (empty($produitIds)) {
            return $this->redirectToRoute('app_produit_index');
        }
    
        // Récupération des produits en base de données
        $produits = $entityManager->getRepository(Produit::class)->findBy(['id' => $produitIds]);
    
        return $this->render('commande/commande.html.twig', [
            'produits' => $produits,
        ]);
    }
    
    #[Route('/commande/finaliser', name: 'finaliser_commande', methods: ['POST'])]
    public function finaliserCommande(Request $request, EntityManagerInterface $entityManager): Response
    {
     $produitIds = $request->request->all('produits');
     $quantites = $request->request->all('quantites');
    

     if (empty($produitIds)) {
         return $this->redirectToRoute('app_produit_index');
     }


     $produits = $entityManager->getRepository(Produit::class)->findBy(['id' => $produitIds]);

     $commande = new Commande();
     $commande->setDateCommande(new \DateTime());
     $commande->setMontantTotal(0);
     $entityManager->persist($commande);
     $entityManager->flush();
  
     $total = 0;

     foreach ($produits as $produit) {
        if (!$produit) {
            throw new \Exception("Produit non trouvé !");
        }
        
   // $subtotal = $produit->getPrix() * $quantite;

        $quantity = isset($quantites[$produit->getId()]) ? (int)$quantites[$produit->getId()] : 1;
       
        $subtotal = $produit->getPrix() * $quantity;
      

         $commandeDetail = new CommandeDetail();
         $commandeDetail->setCommande($commande);
         $commandeDetail->setProduit($produit);
         $commandeDetail->setQuantite($quantity);
         $commandeDetail->setPrixUnitaire($produit->getPrix());
         $commandeDetail->setMontantTotal($subtotal);
        
        
         $entityManager->persist($commandeDetail);
         $entityManager->flush();
         $total += $subtotal;
        //  dump($commandeDetail);
        //  die();
     }

     $commande->setMontantTotal($total);
     $entityManager->flush();

     return $this->redirectToRoute('app_produit_index');
 }


}

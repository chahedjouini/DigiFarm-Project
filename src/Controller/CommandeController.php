<?php

namespace App\Controller;
use TCPDF;
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
use App\Form\StatistiquesVentesType;



#[Route('/commande')]
final class CommandeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    // //  AFFICHAGE DES COMMANDES EN MODE ADMIN
     #[Route('/list', name: 'app_commande_gestion', methods: ['GET'])]
     public function indexBack(CommandeRepository $commandeRepository): Response
     {
         return $this->render('commande/List.html.twig', [
             'commandes' => $commandeRepository->findAll(),
         ]);
     }

     #[Route('/statistiques', name: 'statistiques')]
     public function statistiques(Request $request, CommandeRepository $commandeRepository): Response
     {
     $form = $this->createForm(StatistiquesVentesType::class);
     $form->handleRequest($request);
 
     $commandes = [];
     $ventesParJour = []; // Tableau pour stocker les ventes par mois
     $dates = []; // Tableau pour stocker les mois
     $montants = []; // Tableau pour stocker les montants
 
     if ($form->isSubmitted() && $form->isValid()) {
         // Récupérer les dates de début et de fin
         $startDate = $form->get('start_date')->getData();
         $endDate = $form->get('end_date')->getData();
 
         // Appel du repository pour récupérer les commandes sur cette période
         $commandes = $commandeRepository->findByPeriod($startDate, $endDate);
 
         // Calculer les ventes par mois (exemple de traitement des données)
         foreach ($commandes as $commande) {
             $day = $commande->getDateCommande()->format('Y-m-d'); // Formater la date pour obtenir le mois
             if (!isset($ventesParJour[$day])) {
                 $ventesParJour[$day] = 0;
             }
             $ventesParJour[$day] += $commande->getMontantTotal(); // Ajouter au total des ventes pour ce mois
         }
 
         // Récupérer les clés (mois) et les valeurs (ventes)
         $dates = array_keys($ventesParJour);
         $montants = array_values($ventesParJour);
     }
 
     return $this->render('commande/statistiques.html.twig', [
         'form' => $form->createView(),
         'commandes' => $commandes,
         'dates' => $dates, // Passer les mois
         'montants' => $montants, // Passer les montants
     ]);
     }
 
     #[Route('/export/pdf', name: 'export_sales_pdf')]
     public function exportSalesPdf(Request $request): Response
     {
     $startDate = $request->query->get('start_date');
     $endDate = $request->query->get('end_date');
 
     // Logique pour récupérer les commandes entre startDate et endDate
     $commandes = $this->entityManager->getRepository(Commande::class)->findByPeriod(new \DateTime($startDate), new \DateTime($endDate)); 
 
     // Création du PDF avec TCPDF ou FPDF
     $pdf = new TCPDF();
     $pdf->AddPage();
     $pdf->SetFont('helvetica', '', 12);
     $pdf->Write(0, "Ventes entre $startDate et $endDate");
 
     foreach ($commandes as $commande) {
         $pdf->Write(0, "Commande ID: {$commande->getId()}, Montant: {$commande->getMontantTotal()} €");
     }
 
     $pdf->Output('sales_report.pdf', 'I');
     
     return new Response('PDF généré avec succès');
 }



    
    //*** Finalisation commande */
    #[Route( name: 'commande_page', methods: ['GET', 'POST'])]
    public function commander(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les IDs des produits sélectionnés
        $produitIds = $request->request->all('produits'); // Utilisation de all() pour récupérer un tableau
    
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

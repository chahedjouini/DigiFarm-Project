<?php

namespace App\Controller;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Entity\CommandeDetail;
use App\Repository\CommandeDetailRepository;
use App\Entity\Produit;
use App\Repository\ProduitRepository;


class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/checkout/{id}', name: 'checkout')]
    public function checkout($stripeSK,int $id, CommandeRepository $commandeRepository,
                            CommandeDetailRepository $commandeDetailRepository,ProduitRepository $produitRepository): Response
    {
        Stripe::setApiKey($stripeSK);

        $commande = $commandeRepository->find($id);

        // Récupérer tous les détails de la commande
        $commandeDetails = $commandeDetailRepository->findBy(['commande' => $commande]);
        $shippingAmount = 300; //3usd
        if (!$commande) {
          throw $this->createNotFoundException('Commande non trouvée');
        }
                // Générer les items pour Stripe
                $lineItems = [];
                foreach ($commande->getCommandeDetails() as $detail) {
                    $produit = $detail->getProduit();
                    $productName = $produit ? $produit->getNom() : "Produit non disponible";
                    $lineItems[] = [
                        'price_data' => [
                            'currency'     => 'usd',
                            'product_data' => [
                                'name' => $productName,
                                
                            ],
                            'unit_amount'  => $detail->getPrixUnitaire() * 100, // Convertir en cents
                        ],
                        'quantity'   => $detail->getQuantite(),
                    ];
                }
                $shippingAmount = 300; //3usd
                $lineItems[] = [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => 'Frais de livraison', // Nom pour le coût de livraison
                        ],
                        'unit_amount'  => $shippingAmount, // Montant des frais de livraison (en centimes)
                    ],
                    'quantity'   => 1, // Quantité est 1 pour les frais de livraison
                ];
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', [] ,UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('payment/success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
}

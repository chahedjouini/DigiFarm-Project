<?php
namespace App\Service;

use TCPDF;
use App\Repository\CommandeRepository;
use DateTime;

class PdfGeneratorService
{
    public function generateSalesReport(array $commandes, DateTime $startDate, DateTime $endDate): TCPDF
    {
         // Création du PDF
         $pdf = new TCPDF();
         $pdf->SetAutoPageBreak(TRUE, 15);
         $pdf->AddPage();
         
         // Définition des fonts
         $pdf->SetFont('helvetica', 'B', 16);
         
         // Titre
         $pdf->Cell(0, 10, 'Rapport des Ventes', 0, 1, 'C');
         $pdf->SetFont('helvetica', '', 12);
         $pdf->Cell(0, 10, 'Période: ' . $startDate->format('Y-m-d') . ' à ' . $endDate->format('Y-m-d'), 0, 1, 'C');
         
         // Ajouter un logo ou une image agricole en haut
        //  $pdf->Image('public/assets/images/loader.png', 10, 10, 30);
        // Définir le chemin de l'image
         $imagePath = 'public/assets/images/loader.png'; // Assurez-vous que ce chemin est correct


$x = 10;  // Position horizontale (gauche)
$y = 10;  // Position verticale (haut)
$w = 30;  // Largeur de l'image
$h = 0;   // Hauteur de l'image (0 signifie ajuster automatiquement)

$pdf->Image($imagePath, $x, $y, $w, $h); // Utilisez la variable correcte ici

       
         
         // Tableau des commandes
         $pdf->SetFillColor(229, 238, 188); // Couleur de fond pour le tableau
         $pdf->Cell(40, 10, 'ID Commande', 1, 0, 'C', 1);
         $pdf->Cell(50, 10, 'Date de Commande', 1, 0, 'C', 1);
         $pdf->Cell(50, 10, 'Montant Total (USD)', 1, 0, 'C', 1);
         $pdf->Cell(50, 10, 'Statut', 1, 1, 'C', 1);
 
         // Remplir les données des commandes
         foreach ($commandes as $commande) {
             $pdf->Cell(40, 10, $commande->getId(), 1, 0, 'C');
             $pdf->Cell(50, 10, $commande->getDateCommande()->format('Y-m-d'), 1, 0, 'C');
             $pdf->Cell(50, 10, $commande->getMontantTotal() . ' USD', 1, 0, 'C');
             $pdf->Cell(50, 10, $commande->getStatut(), 1, 1, 'C');
         }
 
         // Total des ventes
         $totalSales = array_sum(array_map(function ($commande) {
             return $commande->getMontantTotal();
         }, $commandes));
 
         $pdf->Ln(10);
         $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(120, 7, ' Total des Ventes :', 0, 0, 'R');
        $pdf->Cell(40, 7, number_format($totalSales, 2, ',', ' ') . ' €', 1, 1, 'C');

 
         // Footer
         $pdf->SetY(-15);
         $pdf->SetFont('helvetica', 'I', 8);
         $pdf->Cell(0, 10, 'Merci pour votre soutien à l\'agriculture durable !', 0, 1, 'C');
 
         return $pdf;
     }
}

<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
// use App\Enum\EnumStatutCommande;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column(enumType: EnumStatutCommande::class)]
    // private ?EnumStatutCommande $statut = null;

    #[ORM\Column(length: 100)]  
    #[Assert\NotBlank(message: "Le type de commande est requis.")]
    #[Assert\Choice(choices: ['Achat', 'Vente'], message: "Le type doit être 'Achat' ou 'Vente'.")]
    private ?string $type = null;


    #[ORM\Column(length: 100)]  
    #[Assert\NotBlank(message: "Le statut de la commande est requis.")]
    #[Assert\Choice(choices: ['en_cours', 'validée', 'livrée', 'annulée'], message: "Statut invalide.")]
    private ?string $statut = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "La quantité est obligatoire.")]
    #[Assert\Positive(message: "La quantité doit être un nombre positif.")]
    private ?float $quantite = null; // Sans accent

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "Le prix unitaire est obligatoire.")]
    #[Assert\Positive(message: "Le prix unitaire doit être un nombre positif.")]
    private ?float $prixUnitaire = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le montant total est obligatoire.")]
    #[Assert\Positive(message: "Le montant total doit être un nombre positif.")]
    private ?float $montantTotal = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date de commande est requise.")]
    #[Assert\LessThanOrEqual('today', message: "La date de commande ne peut pas être dans le futur.")]
    private ?\DateTimeInterface $dateCommande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getQuantite(): ?float // Sans accent
    {
        return $this->quantite;
    }

    public function setQuantite(?float $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(?float $prixUnitaire): static
    {
        $this->prixUnitaire = $prixUnitaire;
        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): static
    {
        $this->montantTotal = $montantTotal;
        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

//     public function getStatut(): ?EnumStatutCommande
//     {
//         return $this->statut;
//     }

//     public function setStatut(?EnumStatutCommande $statut): static
//     {
//         $this->statut = $statut;
//         return $this;
//     }

public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

   
 }

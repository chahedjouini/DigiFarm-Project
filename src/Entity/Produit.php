<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
// use App\Enum\EnumTypeProduit;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column(enumType: EnumTypeProduit::class)] 
    // private ?EnumTypeProduit $type = null;
    // #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'produits')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?Commande $commande = null;

    
    #[ORM\Column(length: 20)]  
    #[Assert\NotBlank(message: "Le type de produit est requis.")]
    #[Assert\Choice(choices: ['Achat', 'Vente'], message: "Le type doit être 'Achat' ou 'Vente'.")]
    private ?string $type = null;


    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "La référence est obligatoire.")]
    #[Assert\Length(min: 3, max: 50, minMessage: "La référence doit faire au moins {{ limit }} caractères.")]
    #[Assert\Regex(pattern: '/^[A-Za-z0-9]+$/', message: "La référence ne doit contenir que des lettres et chiffres.")]
    private ?string $reference = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le nom du produit est requis.")]
    #[Assert\Length(min: 3, max: 50, minMessage: "Le nom doit contenir au moins {{ limit }} caractères.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "La description ne doit pas dépasser {{ limit }} caractères.")]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "Le prix unitaire est obligatoire.")]
    #[Assert\Positive(message: "Le prix unitaire doit être un nombre positif.")]
    private ?float $prix = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "Le stock est obligatoire.")]
    #[Assert\PositiveOrZero(message: "Le stock ne peut pas être négatif.")]
    private ?float $stock = null;

    
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

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
   

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }
    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getStock(): ?float
    {
        return $this->stock;
    }

    public function setStock(?float $stock): static
    {
        $this->stock = $stock;
        return $this;
    }
    public function getImage(): ?string
    {
    return $this->image;
    }

    public function setImage(?string $image): static
    {
    $this->image = $image;
    return $this;
    }
}

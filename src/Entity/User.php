<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\UserRole;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom est obligatoire.")]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le prénom est obligatoire.")]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'adresse e-mail est obligatoire.")]
    #[Assert\Email(message: "Veuillez entrer une adresse e-mail valide.")]
    private ?string $AdresseMail = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le mot de passe est obligatoire.")]
    #[Assert\Length(min: 6, minMessage: "Le mot de passe doit contenir au moins 6 caractères.")]
    private ?string $Password = null;

    #[ORM\Column(type: 'string', enumType: UserRole::class)]
    #[Assert\NotBlank(message:"Le rôle est obligatoire.")]
    private UserRole $Role;

    /**
     * @var Collection<int, Machine>
     */
    #[ORM\OneToMany(targetEntity: Machine::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $machines;

    public function __construct()
    {
        $this->machines = new ArrayCollection();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getAdresseMail(): ?string
    {
        return $this->AdresseMail;
    }

    public function setAdresseMail(string $AdresseMail): static
    {
        $this->AdresseMail = $AdresseMail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function getRole(): UserRole
    {
        return $this->Role;
    }

    public function setRole(UserRole $Role): self
    {
        $this->Role = $Role;
        return $this;
    }

    /**
     * @return Collection<int, Machine>
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(Machine $machine): static
    {
        if (!$this->machines->contains($machine)) {
            $this->machines->add($machine);
            $machine->setOwner($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): static
    {
        if ($this->machines->removeElement($machine)) {
            // set the owning side to null (unless already changed)
            if ($machine->getOwner() === $this) {
                $machine->setOwner(null);
            }
        }

        return $this;
    }

}

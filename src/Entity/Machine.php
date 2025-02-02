<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\EtatEquipement;

#[ORM\Entity(repositoryClass: MachineRepository::class)]
class Machine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_achat = null;

    #[ORM\Column(type: Types::STRING, enumType: EtatEquipement::class)]
    private EtatEquipement $etat;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat_pred = null;

    /**
     * @var Collection<int, maintenance>
     */
    #[ORM\OneToMany(targetEntity: maintenance::class, mappedBy: 'idMachine')]
    private Collection $Maintenance;

    public function __construct()
    {
        $this->Maintenance = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->date_achat;
    }

    public function setDateAchat(\DateTimeInterface $date_achat): static
    {
        $this->date_achat = $date_achat;

        return $this;
    }
    public function getEtat(): EtatEquipement
    {
        return $this->etat;
    }

    public function setEtat(EtatEquipement $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getEtatPred(): ?string
    {
        return $this->etat_pred;
    }

    public function setEtatPred(?string $etat_pred): static
    {
        $this->etat_pred = $etat_pred;

        return $this;
    }

    /**
     * @return Collection<int, maintenance>
     */
    public function getMaintenance(): Collection
    {
        return $this->Maintenance;
    }

    public function addMaintenance(maintenance $maintenance): static
    {
        if (!$this->Maintenance->contains($maintenance)) {
            $this->Maintenance->add($maintenance);
            $maintenance->setIdMachine($this);
        }

        return $this;
    }

    public function removeMaintenance(maintenance $maintenance): static
    {
        if ($this->Maintenance->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getIdMachine() === $this) {
                $maintenance->setIdMachine(null);
            }
        }

        return $this;
    }
}

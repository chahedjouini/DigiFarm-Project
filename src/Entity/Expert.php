<?php

namespace App\Entity;
use App\Enum\Dispo;
use App\Repository\ExpertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpertRepository::class)]
class Expert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?int $tel = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 25)]
    private ?string $zone = null;

    /**
     * @var Collection<int, Etude>
     */
    #[ORM\OneToMany(targetEntity: Etude::class, mappedBy: 'expert')]
    private Collection $etudes;

    #[ORM\Column(enumType: dispo::class)]
    private ?dispo $dispo = null;

    public function __construct()
    {
        $this->etudes = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * @return Collection<int, Etude>
     */
    public function getEtudes(): Collection
    {
        return $this->etudes;
    }

    public function addEtude(Etude $etude): static
    {
        if (!$this->etudes->contains($etude)) {
            $this->etudes->add($etude);
            $etude->setExpert($this);
        }

        return $this;
    }

    public function removeEtude(Etude $etude): static
    {
        if ($this->etudes->removeElement($etude)) {
            // set the owning side to null (unless already changed)
            if ($etude->getExpert() === $this) {
                $etude->setExpert(null);
            }
        }

        return $this;
    }

    public function getDispo(): ?dispo
    {
        return $this->dispo;
    }

    public function setDispo(dispo $dispo): static
    {
        $this->dispo = $dispo;

        return $this;
    }
}

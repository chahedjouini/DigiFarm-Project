<?php

namespace App\Entity;
use App\Enum\StatutMaintenance;
use App\Repository\MaintenanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaintenanceRepository::class)]
class Maintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEntretien = null;

    #[ORM\Column]
    private ?float $cout = null;

    #[ORM\Column(nullable: true)]
    private ?int $temperature = null;

    #[ORM\Column(nullable: true)]
    private ?int $humidite = null;

    #[ORM\Column(nullable: true)]
    private ?float $consoCarburant = null;

    #[ORM\Column(nullable: true)]
    private ?float $consoEnergie = null;

    #[ORM\Column(enumType: StatutMaintenance::class)]
    private ?StatutMaintenance $Status = null;

    #[ORM\ManyToOne(inversedBy: 'Maintenance')]
    #[ORM\JoinColumn(nullable: false)] 
    private ?Machine $idMachine = null;

    #[ORM\ManyToOne(inversedBy: 'Maintenance')]
    #[ORM\JoinColumn(nullable: false)] 
    private ?Technicien $idTechnicien = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDateEntretien(): ?\DateTimeInterface
    {
        return $this->dateEntretien;
    }

    public function setDateEntretien(\DateTimeInterface $dateEntretien): static
    {
        $this->dateEntretien = $dateEntretien;

        return $this;
    }

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(float $cout): static
    {
        $this->cout = $cout;

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature(?int $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getHumidite(): ?int
    {
        return $this->humidite;
    }

    public function setHumidite(?int $humidite): static
    {
        $this->humidite = $humidite;

        return $this;
    }

    public function getConsoCarburant(): ?float
    {
        return $this->consoCarburant;
    }

    public function setConsoCarburant(?float $consoCarburant): static
    {
        $this->consoCarburant = $consoCarburant;

        return $this;
    }

    public function getConsoEnergie(): ?float
    {
        return $this->consoEnergie;
    }

    public function setConsoEnergie(?float $consoEnergie): static
    {
        $this->consoEnergie = $consoEnergie;

        return $this;
    }

    public function getStatus(): ?StatutMaintenance
    {
        return $this->Status;
    }

    public function setStatus(StatutMaintenance $Status): static
    {
        $this->Status = $Status;

        return $this;
    }

    public function getIdMachine(): ?Machine
    {
        return $this->idMachine;
    }

    public function setIdMachine(?Machine $idMachine): static
    {
        $this->idMachine = $idMachine;

        return $this;
    }

    public function getIdTechnicien(): ?Technicien
    {
        return $this->idTechnicien;
    }

    public function setIdTechnicien(?Technicien $idTechnicien): static
    {
        $this->idTechnicien = $idTechnicien;

        return $this;
    }
}

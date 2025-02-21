<?php

namespace App\Entity\Facturation;

use App\Repository\Facturation\PrestationDiversConsolidePrestationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationDiversConsolidePrestationRepository::class)]
class PrestationDiversConsolidePrestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'prestationDiversConsolidePrestations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?PrestationDiversConsolide $prestation_divers_consolide = null;

    #[ORM\Column]
    private ?int $qte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $prix_unitaire_ht = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $prix_total_ht = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $data = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrestationDiversConsolide(): ?PrestationDiversConsolide
    {
        return $this->prestation_divers_consolide;
    }

    public function setPrestationDiversConsolide(?PrestationDiversConsolide $prestation_divers_consolide): static
    {
        $this->prestation_divers_consolide = $prestation_divers_consolide;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getPrixUnitaireHt(): ?string
    {
        return $this->prix_unitaire_ht;
    }

    public function setPrixUnitaireHt(?string $prix_unitaire_ht): static
    {
        $this->prix_unitaire_ht = $prix_unitaire_ht;

        return $this;
    }

    public function getPrixTotalHt(): ?string
    {
        return $this->prix_total_ht;
    }

    public function setPrixTotalHt(?string $prix_total_ht): static
    {
        $this->prix_total_ht = $prix_total_ht;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? (string) $this->id : 'New';
    }
}

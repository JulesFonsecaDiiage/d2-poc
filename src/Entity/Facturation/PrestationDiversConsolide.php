<?php

namespace App\Entity\Facturation;

use App\Entity\Default\Entite;
use App\Repository\Facturation\PrestationDiversConsolideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationDiversConsolideRepository::class)]
class PrestationDiversConsolide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BINARY)]
    private $uuid_entite;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $periode = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $total_ht = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_facture = null;

    /**
     * @var Collection<int, PrestationDiversConsolidePrestation>
     */
    #[ORM\OneToMany(targetEntity: PrestationDiversConsolidePrestation::class, mappedBy: 'prestation_divers_consolide')]
    private Collection $prestationDiversConsolidePrestations;

    public function __construct()
    {
        $this->prestationDiversConsolidePrestations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuidEntite()
    {
        return $this->uuid_entite;
    }

    public function setUuidEntite($uuid_entite): static
    {
        $this->uuid_entite = $uuid_entite;

        return $this;
    }

    public function getPeriode(): ?\DateTimeInterface
    {
        return $this->periode;
    }

    public function setPeriode(\DateTimeInterface $periode): static
    {
        $this->periode = $periode;

        return $this;
    }

    public function getTotalHt(): ?string
    {
        return $this->total_ht;
    }

    public function setTotalHt(?string $total_ht): static
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    public function getIdFacture(): ?int
    {
        return $this->id_facture;
    }

    public function setIdFacture(?int $id_facture): static
    {
        $this->id_facture = $id_facture;

        return $this;
    }

    /**
     * @return Collection<int, PrestationDiversConsolidePrestation>
     */
    public function getPrestationDiversConsolidePrestations(): Collection
    {
        return $this->prestationDiversConsolidePrestations;
    }

    public function addPrestationDiversConsolidePrestation(PrestationDiversConsolidePrestation $prestationDiversConsolidePrestation): static
    {
        if (!$this->prestationDiversConsolidePrestations->contains($prestationDiversConsolidePrestation)) {
            $this->prestationDiversConsolidePrestations->add($prestationDiversConsolidePrestation);
            $prestationDiversConsolidePrestation->setPrestationDiversConsolide($this);
        }

        return $this;
    }

    public function removePrestationDiversConsolidePrestation(PrestationDiversConsolidePrestation $prestationDiversConsolidePrestation): static
    {
        if ($this->prestationDiversConsolidePrestations->removeElement($prestationDiversConsolidePrestation)) {
            // set the owning side to null (unless already changed)
            if ($prestationDiversConsolidePrestation->getPrestationDiversConsolide() === $this) {
                $prestationDiversConsolidePrestation->setPrestationDiversConsolide(null);
            }
        }

        return $this;
    }
}

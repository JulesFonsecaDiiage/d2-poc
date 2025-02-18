<?php

namespace App\Entity;

use App\Repository\EntiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntiteRepository::class)]
class Entite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BINARY)]
    private $uuid;

    /**
     * @var Collection<int, PrestationDiversConsolide>
     */
    #[ORM\OneToMany(targetEntity: PrestationDiversConsolide::class, mappedBy: 'entite', orphanRemoval: true)]
    private Collection $prestationDiversConsolides;

    public function __construct()
    {
        $this->prestationDiversConsolides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return Collection<int, PrestationDiversConsolide>
     */
    public function getPrestationDiversConsolides(): Collection
    {
        return $this->prestationDiversConsolides;
    }

    public function addPrestationDiversConsolide(PrestationDiversConsolide $prestationDiversConsolide): static
    {
        if (!$this->prestationDiversConsolides->contains($prestationDiversConsolide)) {
            $this->prestationDiversConsolides->add($prestationDiversConsolide);
            $prestationDiversConsolide->setEntite($this);
        }

        return $this;
    }

    public function removePrestationDiversConsolide(PrestationDiversConsolide $prestationDiversConsolide): static
    {
        if ($this->prestationDiversConsolides->removeElement($prestationDiversConsolide)) {
            // set the owning side to null (unless already changed)
            if ($prestationDiversConsolide->getEntite() === $this) {
                $prestationDiversConsolide->setEntite(null);
            }
        }

        return $this;
    }
}
<?php

namespace App\Entity\Default;

use App\Repository\Default\EntiteRepository;
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

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    /**
     * @var Collection<int, Expedition>
     */
    #[ORM\OneToMany(targetEntity: Expedition::class, mappedBy: 'entity')]
    private Collection $expeditions;

    public function __construct()
    {
        $this->expeditions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid()
    {
        if (is_resource($this->uuid)) {
            return stream_get_contents($this->uuid);
        }

        return $this->uuid;
    }

    public function setUuid($uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Expedition>
     */
    public function getExpeditions(): Collection
    {
        return $this->expeditions;
    }

    public function addExpedition(Expedition $expedition): static
    {
        if (!$this->expeditions->contains($expedition)) {
            $this->expeditions->add($expedition);
            $expedition->setEntity($this);
        }

        return $this;
    }

    public function removeExpedition(Expedition $expedition): static
    {
        if ($this->expeditions->removeElement($expedition)) {
            // set the owning side to null (unless already changed)
            if ($expedition->getEntity() === $this) {
                $expedition->setEntity(null);
            }
        }

        return $this;
    }
}
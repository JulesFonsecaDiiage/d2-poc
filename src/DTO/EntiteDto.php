<?php

namespace App\DTO;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class EntiteDto
{
    public function __construct(
    private int $id,
    private Uuid $uuid,
    private Collection $prestationDiversConsolides,
    private bool $active,
    private string $name,
    private string $email,
    private float $montantTotalPrestationDiversConsolide,
    private int $nombrePrestationDiversConsolidePrestation,
    ) {}
}
<?php

namespace App\Service;

use App\Repository\Default\EntiteRepository;
use App\Repository\Facturation\PrestationDiversConsolidePrestationRepository;
use App\Repository\Facturation\PrestationDiversConsolideRepository;

class EntiteService
{
    public function __construct(
        private EntiteRepository $entiteRepository,
        private PrestationDiversConsolideRepository $prestationDiversConsolideRepository,
        private PrestationDiversConsolidePrestationRepository $prestationDiversConsolidePrestationRepository
    ) {}

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        $data = [];

        $entites = $this->entiteRepository->findBy($criteria, $orderBy, $limit, $offset);

        foreach ($entites as $entite) {
            $prestationDiversConsolides = $this->prestationDiversConsolideRepository->findBy(['entite' => $entite->getId()]);

            $montantTotalPrestationDiversConsolide = 0;
            $nombrePrestationDiversConsolidePrestation = 0;

            foreach ($prestationDiversConsolides as $prestationDiversConsolide) {
                $montantTotalPrestationDiversConsolide += $prestationDiversConsolide->getMontant();
                $nombrePrestationDiversConsolidePrestation += $this->prestationDiversConsolidePrestationRepository->count(['prestationDiversConsolide' => $prestationDiversConsolide->getId()]);
            }

            $data[] = [
                'id' => $entite->getId(),
                'uuid' => $entite->getUuid(),
                'prestationDiversConsolides' => $prestationDiversConsolides,
                'active' => $entite->getActive(),
                'name' => $entite->getName(),
                'email' => $entite->getEmail(),
                'montantTotalPrestationDiversConsolide' => $montantTotalPrestationDiversConsolide,
                'nombrePrestationDiversConsolidePrestation' => $nombrePrestationDiversConsolidePrestation,
            ];
        }

        return $data;
    }
}
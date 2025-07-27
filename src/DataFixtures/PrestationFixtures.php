<?php

namespace App\DataFixtures;

use App\Entity\Default\Entite;
use App\Entity\Facturation\PrestationDiversConsolide;
use App\Entity\Facturation\PrestationDiversConsolidePrestation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PrestationFixtures extends Fixture implements FixtureGroupInterface
{
    private Generator $faker;
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->faker = Factory::create('fr_FR');
        $this->managerRegistry = $managerRegistry;
    }

    public function load(ObjectManager $manager): void
    {
        // PrestationDiversConsolides
        for ($i = 0; $i < 10000; ++$i) {
            $defaultManager = $this->managerRegistry->getManager('default'); // Prend l'EM "default"
            $entite = $defaultManager->getRepository(Entite::class)->findBy([], null, 1, $this->faker->numberBetween(0,9999))[0]; // Récupère une entité

            $prestationDiversConsolide = new PrestationDiversConsolide();
            $prestationDiversConsolide->setUuidEntite($entite->getUuid());
            $prestationDiversConsolide->setPeriode($this->faker->dateTimeBetween('-2 years')->modify('first day of this month'));

            $manager->persist($prestationDiversConsolide);
            $this->addReference(PrestationDiversConsolide::class . '_' . $i, $prestationDiversConsolide);
        }

        // PrestationDiversConsolidesPrestations
        for ($i = 0; $i < 100000; ++$i) {
            $prestationDiversConsolide = $this->getReference(PrestationDiversConsolide::class . '_' . $this->faker->numberBetween(0, 9999), PrestationDiversConsolide::class);

            $prestationDiversConsolidePrestation = new PrestationDiversConsolidePrestation();
            $prestationDiversConsolidePrestation->setPrestationDiversConsolide($prestationDiversConsolide);
            $prestationDiversConsolidePrestation->setPrixUnitaireHt($this->faker->randomFloat(2, 1, 100));
            $prestationDiversConsolidePrestation->setQte($this->faker->numberBetween(1, 100));
            $prestationDiversConsolidePrestation->setPrixTotalHt($prestationDiversConsolidePrestation->getPrixUnitaireHt() * $prestationDiversConsolidePrestation->getQte());
            $prestationDiversConsolidePrestation->setData($this->faker->text);

            // On met à jour le total HT de la prestation divers consolide
            $prestationDiversConsolide->setTotalHt($prestationDiversConsolide->getTotalHt() + $prestationDiversConsolidePrestation->getPrixTotalHt());

            $manager->persist($prestationDiversConsolidePrestation);
            $manager->persist($prestationDiversConsolide);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['facturation'];
    }
}
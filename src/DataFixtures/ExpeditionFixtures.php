<?php

namespace App\DataFixtures;

use App\Entity\Default\Entite;
use App\Entity\Default\Expedition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ExpeditionFixtures extends Fixture implements FixtureGroupInterface
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
        // Expeditions
        for ($i = 0; $i < 1000; ++$i) {
            $defaultManager = $this->managerRegistry->getManager('default'); // Prend l'EM "default"
            $entite = $defaultManager->getRepository(Entite::class)->findBy([], null, 1, $this->faker->numberBetween(0,199))[0]; // Récupère une entité

            $expedition = new Expedition();
            $expedition->setCode(strtoupper($this->faker->unique()->bothify('EXP-######??')));
            $expedition->setEntity($entite);
            $expedition->setTotal($this->faker->randomFloat(2, 100, 10000));
            $expedition->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year')));

            $manager->persist($expedition);
            $this->addReference(Expedition::class . '_' . $i, $expedition);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['default'];
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Default\Entite;
use App\Entity\Facturation\PrestationDiversConsolide;
use App\Entity\Facturation\PrestationDiversConsolidePrestation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class EntiteFixtures extends Fixture implements FixtureGroupInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 200; ++$i) {
            $entite = new Entite();
            $entite->setName($this->faker->company);
            $entite->setEmail($this->faker->companyEmail);
            $entite->setActive($this->faker->boolean);
            $entite->setUuid($this->faker->uuid);

            $manager->persist($entite);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['default'];
    }
}
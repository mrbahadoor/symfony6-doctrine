<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\VinylMix;
use App\Factory\VinylMixFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        VinylMixFactory::createMany(25);
    }
}

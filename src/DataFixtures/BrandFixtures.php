<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++){
            $brand = new Brand();
            $brand->setName("Brand $i");
            $brand->setOrigin("USA");

            $manager->persist($brand);
        }

        $manager->flush();
    }
}

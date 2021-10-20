<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++){
            $product = new Product();
            $product
                    ->setName("Product $i")
                    ->setPrice(mt_rand(10, 600))
                    ->setDescription("San pham nay .....")
                    ->setImage("product1.png");
    
            $manager->persist($product);
        }

        $manager->flush();
    }
}

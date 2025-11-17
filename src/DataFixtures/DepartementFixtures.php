<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DepartementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 1; $i <= 10; $i++) {
            $departement = new \App\Entity\Departement();
            $departement->setName("Departement $i");
            
             $departement->setUpdateAt(new \DateTimeImmutable());
            $manager->persist($departement);
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i = 0; $i < 5; $i++){
            $departement = new Department();
            $departement->setNameDepartment("Service ptaleau" . $i)
                ->setMailDepartment("service@plateau" . $i . ".com");
            $manager->persist($departement);
            $manager->flush();
        }
    }
}

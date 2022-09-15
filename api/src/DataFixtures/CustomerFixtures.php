<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i=0;$i<20;$i++){
            $object=(new Customer)
                ->setFirstname($faker->name())
                ->setLastname($faker->name())
                ->setPhone($faker->phoneNumber())
                ->setAddress($faker->address());

            $manager->persist($object);
        }

        $manager->flush();
    }
}

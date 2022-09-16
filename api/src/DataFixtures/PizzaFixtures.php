<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Pizza;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class PizzaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $ingredients = $manager->getRepository(Ingredient::class)->findAll();
      

        for($i=0;$i<10;$i++){
            $object=(new Pizza())
                ->setName($faker->name())
                ->setDescription($faker->paragraph())
                ;

            for($j=0;$j< $faker->numberBetween(3, 8); $j++){
                $object->addIngredient($faker->randomElement($ingredients));
            }

            $manager->persist($object);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            IngredientFixtures::class,
            UserFixtures::class
        ];
    }
}

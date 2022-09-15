<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Order;
use App\Entity\Pizza;
use App\Entity\Detail;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DetailFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $pizzas = $manager->getRepository(Pizza::class)->findAll();
        $orders = $manager->getRepository(Order::class)->findAll();
        $sizes = [ "s", "m", "l", "xl" ];

        foreach ($orders as $order) {
            for($i=0;$i < $faker->numberBetween(0,10);$i++){
                $object = (new Detail())
                        ->setPrice($faker->randomNumber())
                        ->setSize($faker->randomElement($sizes))
                        ->setOrderDelivery($order);
                $object->setPizza($faker->randomElement($pizzas));
                $manager->persist($object);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PizzaFixtures::class,
            OrderFixtures::class
        ];
    }
}

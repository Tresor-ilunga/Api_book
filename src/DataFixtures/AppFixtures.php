<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence())
                ->setCoverText($faker->text(200));

        $manager->persist($book);
        }
        $manager->flush();
    }
}

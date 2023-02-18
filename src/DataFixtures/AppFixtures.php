<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Creation des auteurs
        $listAuthor = [];
        for($i = 0; $i < 10; $i++) {
            $author = new Author();
            $author->setFirstName($faker->firstName())
                ->setLastName($faker->lastName());

            $manager->persist($author);
            // On sauvergarde l'auteur dans un tableau
            $listAuthor[] = $author;
        }


        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence())
                ->setCoverText($faker->text(200));
            $book->setAuthor($listAuthor[array_rand($listAuthor)] );

        $manager->persist($book);
        }
        $manager->flush();
    }
}

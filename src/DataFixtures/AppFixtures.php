<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory;
use App\Entity\Book;
use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class AppFixtures
 *
 * @author Tresor-ilunga <ilungat82@gmail.com>
 */
class AppFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Creation des utilisateurs
        $user = new User();
        $user->setEmail("user@bookapi.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->passwordHasher->hashPassword($user, "password"));

        $manager->persist($user);

        // Creation d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@bookapi.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin, "password"));

        $manager->persist($userAdmin);

        // Creation des auteurs
        $listAuthor = [];
        for($i = 0; $i < 10; $i++) {
            $author = new Author();
            $author->setFirstName($faker->firstName())
                ->setLastName($faker->lastName());

            $manager->persist($author);
            // On sauvegarde l'auteur dans un tableau
            $listAuthor[] = $author;
        }


        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence())
                ->setCoverText($faker->text(200));
            $book->setAuthor($listAuthor[array_rand($listAuthor)] );
            $book->setComment("Commentaire du bibliothécaire");

        $manager->persist($book);
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Rental;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        
    }



    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Avatar($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        $admin = new User();
        $password = $this->encoder->encodePassword($admin, 'admin');
        $admin->setEmail("admin@gmail.com")
            ->setPassword($password)
            ->setFirstName($faker->firstNameMale())
            ->setLastName($faker->lastName())
            ->setRoles(['ROLE_ADMIN'])
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
            ->setPicture('https://randomuser.me/api/portraits/men/35.jpg')
            ->setCreatedAt($faker->dateTime());

        $manager->persist($admin);

        $users = [];
        $genres = ['male', 'female'];

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $password = $this->encoder->encodePassword($user, 'toto');

            $user->setEmail("user$i@gmail.com")
                ->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastName())
                ->setPassword($password)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setPicture($picture)
                ->setCreatedAt($faker->dateTime());

            $manager->persist($user);
            $users[] = $user;
        }

        $category1 = new Category();
        $category1->setName("Appartement");
        
        $manager->persist($category1);
        
        $category2 = new Category();
        $category2->setName("Maison");
        
        $manager->persist($category2);
        
        $category3 = new Category();
        $category3->setName("Villa VIP");

        $manager->persist($category3);
        


        for ($i = 1; $i <= 10; $i++) {
            $rental = new Rental();

            $title      = $faker->sentence();
            $coverImage = "https://picsum.photos/1200/350?random=" . mt_rand(1, 55000);
            $introduction = $faker->paragraph(2);
            $content    = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $rental->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user)
                ->setCategory($category1)
                ->setCreatedAt($faker->dateTime());

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();

                $image->setUrl("https://picsum.photos/640/480?random=" . mt_rand(0, 55000))
                    ->setCaption($faker->sentence())
                    ->setRental($rental);

                $manager->persist($image);
            }

            $manager->persist($rental);
        }

        for ($i = 1; $i <= 10; $i++) {
            $rental = new Rental();

            $title      = $faker->sentence();
            $coverImage = "https://picsum.photos/1200/350?random=" . mt_rand(1, 55000);
            $introduction = $faker->paragraph(2);
            $content    = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $rental->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user)
                ->setCategory($category2)
                ->setCreatedAt($faker->dateTime());

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();

                $image->setUrl("https://picsum.photos/640/480?random=" . mt_rand(0, 55000))
                    ->setCaption($faker->sentence())
                    ->setRental($rental);

                $manager->persist($image);
            }

            $manager->persist($rental);
        }

        for ($i = 1; $i <= 10; $i++) {
            $rental = new Rental();

            $title      = $faker->sentence();
            $coverImage = "https://picsum.photos/1200/350?random=" . mt_rand(1, 55000);
            $introduction = $faker->paragraph(2);
            $content    = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $rental->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user)
                ->setCategory($category3)
                ->setCreatedAt($faker->dateTime());

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();

                $image->setUrl("https://picsum.photos/640/480?random=" . mt_rand(0, 55000))
                    ->setCaption($faker->sentence())
                    ->setRental($rental);

                $manager->persist($image);
            }

            $manager->persist($rental);
        }
    
        $manager->flush();
    }
}

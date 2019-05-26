<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Answer;
use App\Utils\Slugger;
use App\Entity\Question;
use Cocur\Slugify\Slugify;
use Nelmio\Alice\Loader\NativeLoader;
use App\DataFixtures\Faker\AvatarProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // ******************************************************
        // ******************* Fixtures Mano ********************
        // ******************************************************

        // Génération des role utilisateur (admin et user)
        $roleUser = new Role();
        $roleUser->setCode('ROLE_USER');
        $roleUser->setName('user');
        
        $roleAdmin = new Role();
        $roleAdmin->setCode('ROLE_ADMIN');
        $roleAdmin->setName('admin');

        $roleModerator = new Role();
        $roleModerator->setCode('ROLE_MODERATOR');
        $roleModerator->setName('Moderator');

        $admin = new User();
        //j'encode mon mot de passe avant de le stocker dans ma propriété password
        $encodedPassword = $this->encoder->encodePassword($admin, 'admin');
        $admin->setusername('admin')
            ->setFirstname('OH grand maître')
            ->setLastname('admin')
            ->setEmail('admin@admin.fr')
            ->setPassword($encodedPassword)
            ->setAvatar('http://lorempixel.com/output/food-q-c-64-64-4.jpg')
            ->setRole($roleAdmin);

        $user = new User();
        $encodedPassword = $this->encoder->encodePassword($user, 'user');
        $user->setusername('user')
            ->setFirstname('Simple utilisateur')
            ->setLastname('user')
            ->setEmail('user@user.fr')
            ->setPassword($encodedPassword)
            ->setAvatar('http://lorempixel.com/output/food-q-c-64-64-4.jpg')
            ->setRole($roleUser); 

        $moderator = new User();
        $encodedPassword = $this->encoder->encodePassword($moderator, 'moderator');
        $moderator->setusername('moderator')
            ->setFirstname('Sous chef de grade moderateur')
            ->setLastname('moderator')
            ->setEmail('moderator@moderator.fr')
            ->setPassword($encodedPassword)
            ->setAvatar('http://lorempixel.com/output/food-q-c-64-64-4.jpg')
            ->setRole($roleModerator);

        $manager->persist($roleAdmin);
        $manager->persist($roleUser);
        $manager->persist($roleModerator);
        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($moderator);

        $manager->flush();

        // ******************************************************
        // ******************* Fixtures Alice *******************
        // ******************************************************

        //$loader = new NativeLoader();
        $loader = new MyCustomNativeLoader();
        
        //importe le fichier de fixtures et récupère les entités générés
        $entities = $loader->loadFile(__DIR__.'/fixtures.yml')->getObjects();
        
        //empile la liste d'objet à enregistrer en BDD
        foreach ($entities as $entity) {
            $manager->persist($entity);
        };
        
        //enregistre
        $manager->flush();

        // ******************************************************
        // ********* Exemple Fixtures Faker Générator ***********
        // ******************************************************

        /*
        $generator = Factory::create('fr_FR');
        $generator->addProvider(new AvatarProvider($generator));

        $populator = new Faker\ORM\Doctrine\Populator($generator, $manager);

        $populator->addEntity(User::class, 20, array(
            'username' => function() use ($generator) { return $generator->numerify('user###'); },
            'firstname' => function() use ($generator) { return $generator->firstName(); },
            'lastname' => function() use ($generator) { return $generator->lastName(); },
            'email' => function() use ($generator) { return $generator->email(); },
            'password' => function() use ($generator) { return $generator->password(); },
            'avatar' => function() use ($generator) { return $generator->unique()->avatar(); },
            )
        );

        $populator->addEntity(Answer::class, 50, array(
            'content' => function() use ($generator) { return $generator->paragraph(); },
            'rank' => function() use ($generator) { return $generator->numberBetween(-100, 100); },
            )
        );

        $populator->addEntity(Question::class, 8, array(
            'title' => function() use ($generator) { return $generator->sentence($nbWords = 15, $variableNbWords = true); },
            'content' => function() use ($generator) { return $generator->paragraph(); },
            'rank' => function() use ($generator) { return $generator->numberBetween(-100, 100); },
            )
        );

        $populator->addEntity(Tag::class, 20, array(
            'name' => function() use ($generator) { return $generator->word(); },
            )
        );

        $inserted = $populator->execute();

        $manager->flush(); 
        */

    }
}

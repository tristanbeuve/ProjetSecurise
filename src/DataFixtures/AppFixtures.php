<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    // ...
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setName('Admin');
        $password = $this->hasher->hashPassword($user, 'Not24get');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);


        $user1 = new User();
        $user1->setEmail('ChatJ@iPT.com');
        $user1->setName('ChatJaiPT');
        $password = $this->hasher->hashPassword($user1, 'Not24get');
        $user1->setPassword($password);
        $user1->setRoles(['ROLE_USER']);
        $manager->persist($user1);

        $manager->flush();
    }
}
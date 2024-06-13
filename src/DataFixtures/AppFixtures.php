<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    { 
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail("user@amstamgram.com")
            ->setPassword($this->hasher->hashPassword($user,"user"));
        $manager->persist($user);

        $admin = new User();
        $admin
            ->setEmail("admin@amstamgram.com")
            ->setPassword($this->hasher->hashPassword($admin,"admin"))
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        $manager->flush();
    }
}

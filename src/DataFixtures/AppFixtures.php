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
            ->setUsername("user")
            ->setRoles(["ROLE_USER"])
            ->setPassword($this->hasher->hashPassword($user,"user"))
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user);

        $admin = new User();
        $admin
            ->setEmail("admin@amstamgram.com")
            ->setUsername("admin")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->hasher->hashPassword($admin,"admin"))
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($admin);

        $manager->flush();
    }
}

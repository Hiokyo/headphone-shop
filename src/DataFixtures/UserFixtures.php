<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("user");
        $user->setPassword($this->hasher->hashpassword($user, "123456"));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);

        $user = new User();
        $user->setUsername("admin");
        $user->setPassword($this->hasher->hashpassword($user, "123456"));
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);

        $manager->flush();
    }
}

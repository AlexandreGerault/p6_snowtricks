<?php

declare(strict_types=1);

namespace App\Security\Entity;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {

    }

    public function create(string $username, string $email, string $password): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        return $user;
    }
}

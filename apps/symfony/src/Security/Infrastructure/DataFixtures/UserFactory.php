<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\DataFixtures;

use App\Security\Infrastructure\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private UserPasswordHasherInterface $hasher, private bool $active = false)
    {
    }

    public function active(): static
    {
        $this->active = true;

        return $this;
    }

    public function inactive(): static
    {
        $this->active = false;

        return $this;
    }

    public function create(string $username, string $email, string $password): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $user->setActive($this->active);

        return $user;
    }
}

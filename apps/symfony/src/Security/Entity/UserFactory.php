<?php

declare(strict_types=1);

namespace App\Security\Entity;

class UserFactory
{
    public function create(string $username, string $email, string $password): User
    {
        return (new User())
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($password);
    }
}

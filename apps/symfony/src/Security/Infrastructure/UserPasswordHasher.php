<?php

declare(strict_types=1);

namespace App\Security\Infrastructure;

use App\Security\Core\HashedPassword;
use App\Security\Core\PasswordHasher;
use App\Security\Core\PlainPassword;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserPasswordHasher implements PasswordHasher
{
    public function __construct(private readonly PasswordHasherInterface $hasher)
    {
    }

    public function hash(PlainPassword $password): HashedPassword
    {
        return new HashedPassword($this->hasher->hash($password->value));
    }
}
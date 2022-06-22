<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security\Adapters;

use App\Security\Core\HashedPassword;
use App\Security\Core\PasswordHasher;
use App\Security\Core\PlainPassword;

class FakePasswordHasher implements PasswordHasher
{
    public function hash(PlainPassword $password): HashedPassword
    {
        return new HashedPassword($password->value.'_hashed');
    }
}

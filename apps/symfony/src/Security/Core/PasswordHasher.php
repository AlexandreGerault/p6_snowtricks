<?php

namespace App\Security\Core;

interface PasswordHasher
{
    public function hash(PlainPassword $password): HashedPassword;
}

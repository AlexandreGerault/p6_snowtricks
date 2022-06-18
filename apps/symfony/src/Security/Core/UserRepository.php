<?php

declare(strict_types=1);

namespace App\Security\Core;

interface UserRepository
{
    public function save(User $user): void;
}
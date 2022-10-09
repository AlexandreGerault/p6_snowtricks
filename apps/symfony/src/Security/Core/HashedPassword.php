<?php

declare(strict_types=1);

namespace App\Security\Core;

class HashedPassword
{
    public function __construct(public readonly string $value)
    {
    }
}

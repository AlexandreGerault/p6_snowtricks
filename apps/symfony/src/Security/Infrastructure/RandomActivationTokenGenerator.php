<?php

namespace App\Security\Infrastructure;

use App\Security\Core\ActivationToken;
use App\Security\Core\ActivationTokenGenerator;

class RandomActivationTokenGenerator implements ActivationTokenGenerator
{
    /** @throws \Exception */
    public function generate(): ActivationToken
    {
        return new ActivationToken(bin2hex(random_bytes(16)));
    }
}
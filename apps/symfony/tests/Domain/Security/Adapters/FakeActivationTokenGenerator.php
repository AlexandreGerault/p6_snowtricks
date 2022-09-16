<?php

namespace App\Tests\Domain\Security\Adapters;

use App\Security\Core\ActivationToken;
use App\Security\Core\ActivationTokenGenerator;

class FakeActivationTokenGenerator implements ActivationTokenGenerator
{
    public function generate(): ActivationToken
    {
        return new ActivationToken('fake-token');
    }
}
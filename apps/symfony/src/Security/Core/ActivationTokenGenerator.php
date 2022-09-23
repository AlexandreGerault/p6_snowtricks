<?php

namespace App\Security\Core;

interface ActivationTokenGenerator
{
    public function generate(): ActivationToken;
}

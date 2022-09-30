<?php

namespace App\Shared\Dates;

use DateTimeImmutable;

interface CurrentDateInterface
{
    public function now(): DateTimeImmutable;
}
<?php

namespace App\Shared\Dates;

use DateTimeImmutable;

class CurrentDate implements CurrentDateInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
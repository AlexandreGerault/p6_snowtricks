<?php

namespace App\Tests\Helpers\Dates;

use App\Shared\Dates\CurrentDateInterface;
use DateTimeImmutable;

class FakeCurrentDate implements CurrentDateInterface
{
    public function __construct(private readonly DateTimeImmutable $date)
    {
    }

    public function now(): DateTimeImmutable
    {
        return $this->date;
    }
}

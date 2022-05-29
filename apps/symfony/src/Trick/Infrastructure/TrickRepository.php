<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure;

use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;

class TrickRepository implements TrickGateway
{
    public function save(Trick $trick): void
    {
    }

    public function findAll(): array
    {
        return [];
    }
}

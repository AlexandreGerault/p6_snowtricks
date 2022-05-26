<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\Adapters;

use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;

class InMemoryTrickGateway implements TrickGateway
{
    private array $tricks = [];

    public function save(Trick $trick): void
    {
        $this->tricks[] = $trick;
    }

    public function findAll(): array
    {
        return $this->tricks;
    }
}

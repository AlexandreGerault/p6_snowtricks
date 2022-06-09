<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\Adapters;

use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;
use Symfony\Component\Uid\AbstractUid;

class InMemoryTrickGateway implements TrickGateway
{
    public function __construct(private array $tricks = [])
    {
    }

    public function save(Trick $trick): void
    {
        $this->tricks[] = $trick;
    }

    public function findAll(): array
    {
        return $this->tricks;
    }

    public function get(AbstractUid $trickId): Trick
    {
        return $this->tricks[0];
    }
}

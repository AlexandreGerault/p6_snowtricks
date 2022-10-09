<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\RegisterTrick;

use App\Trick\Core\TrickSnapshot;
use App\Trick\Core\UseCases\Commands\RegisterTrick\RegisterTrickOutputPort;

class RegisterTrickTestOutputPort implements RegisterTrickOutputPort
{
    public readonly TrickSnapshot $snapshot;

    public function trickCreated(TrickSnapshot $trick): void
    {
        $this->snapshot = $trick;
    }

    public function cannotCreateTrick(): void
    {
    }
}

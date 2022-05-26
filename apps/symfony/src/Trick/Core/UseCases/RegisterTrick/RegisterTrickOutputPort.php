<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\RegisterTrick;

use App\Trick\Core\TrickSnapshot;

interface RegisterTrickOutputPort
{
    public function trickCreated(TrickSnapshot $trick): void;

    public function cannotCreateTrick(): void;
}

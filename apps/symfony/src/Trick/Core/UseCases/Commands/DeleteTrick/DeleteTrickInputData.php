<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\Commands\DeleteTrick;

class DeleteTrickInputData
{
    public function __construct(public readonly string $trickId)
    {
    }
}

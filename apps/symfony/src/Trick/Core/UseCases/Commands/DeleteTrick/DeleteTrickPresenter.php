<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\Commands\DeleteTrick;

interface DeleteTrickPresenter
{
    public function trickDeleted(): void;

    public function trickNotFound(): void;
}

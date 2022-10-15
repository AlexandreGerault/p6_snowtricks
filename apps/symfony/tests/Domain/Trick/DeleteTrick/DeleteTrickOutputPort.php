<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\DeleteTrick;

use App\Trick\Core\UseCases\Commands\DeleteTrick\DeleteTrickPresenter;
use PHPUnit\Framework\Assert;

class DeleteTrickOutputPort implements DeleteTrickPresenter
{
    public bool $trickDeleted = false;
    public bool $trickNotFound = false;

    public function trickDeleted(): void
    {
        $this->trickDeleted = true;
    }

    public function trickNotFound(): void
    {
        $this->trickNotFound = true;
    }

    public function assertTrickWasDeleted()
    {
        Assert::assertTrue($this->trickDeleted);
    }
}

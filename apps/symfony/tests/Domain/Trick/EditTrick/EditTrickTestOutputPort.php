<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\EditTrick;

use App\Trick\Core\Trick;
use App\Trick\Core\TrickSnapshot;
use App\Trick\Core\UseCases\EditTrick\EditTrickPresenter;

class EditTrickTestOutputPort implements EditTrickPresenter
{
    public TrickSnapshot $snapshot;

    public function trickEdited(Trick $trick): void
    {
        $this->snapshot = $trick->snapshot();
    }
}

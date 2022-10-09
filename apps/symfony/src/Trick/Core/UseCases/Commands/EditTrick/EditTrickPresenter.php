<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\Commands\EditTrick;

use App\Trick\Core\Trick;

interface EditTrickPresenter
{
    public function trickEdited(Trick $trick): void;
}

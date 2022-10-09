<?php

namespace App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments;

interface GetTrickWithPaginatedCommentsPresenter
{
    public function presents(TrickWithPaginatedCommentsResult $trickWithPaginatedCommentsResult): void;
}

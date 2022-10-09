<?php

namespace App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews;

interface GetPaginatedTrickOverviewsPresenter
{
    public function presents(PaginatedTrickOverviewsResult $trickOverviews): void;
}

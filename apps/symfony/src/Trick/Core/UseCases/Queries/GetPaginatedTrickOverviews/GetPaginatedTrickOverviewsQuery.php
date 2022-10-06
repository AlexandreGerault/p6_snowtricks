<?php

namespace App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews;

interface GetPaginatedTrickOverviewsQuery
{
    public function run(int $limit, int $offset): PaginatedTrickOverviewsResult;
}
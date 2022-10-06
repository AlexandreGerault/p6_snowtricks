<?php

namespace App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews;

class GetPaginatedTrickOverviewsInputData
{
    public function __construct(
        public readonly int $perPage,
        public readonly int $page = 1,
    ) {
    }
}
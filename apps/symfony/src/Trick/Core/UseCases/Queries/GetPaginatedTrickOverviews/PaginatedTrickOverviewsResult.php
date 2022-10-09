<?php

namespace App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews;

class PaginatedTrickOverviewsResult
{
    /**
     * @param array<TrickOverview> $trickOverviews
     */
    public function __construct(
        public readonly int $total,
        public readonly int $perPage,
        public readonly int $page,
        public readonly array $trickOverviews,
    ) {
    }
}

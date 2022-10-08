<?php

namespace App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments;

class GetTrickWithPaginatedCommentsInputData
{
    public function __construct(
        public readonly string $slug,
        public readonly int $perPage,
        public readonly int $page = 1,
    ) {
    }
}

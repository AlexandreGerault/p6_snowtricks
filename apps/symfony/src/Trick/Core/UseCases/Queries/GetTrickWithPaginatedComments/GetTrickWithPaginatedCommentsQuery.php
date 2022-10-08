<?php

namespace App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments;

interface GetTrickWithPaginatedCommentsQuery
{
    public function run(string $slug, int $limit, int $offset): TrickWithPaginatedCommentsResult;
}

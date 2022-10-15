<?php

namespace App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews;

class TrickOverview
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $categoryName,
        public readonly string $thumbnailUrl,
        public readonly int $commentsCount,
    ) {
    }
}

<?php

namespace App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments;

use App\Trick\Core\Image;
use App\Trick\Core\Video;

class TrickWithPaginatedCommentsResult
{
    /**
     * @param array<TrickComment> $comments
     * @param array<Image>        $images
     * @param array<Video>        $videos
     */
    public function __construct(
        public readonly int $total,
        public readonly int $perPage,
        public readonly int $page,
        public readonly string $trickName,
        public readonly string $trickSlug,
        public readonly string $trickCategoryName,
        public readonly string $trickDescription,
        public readonly string $thumbnailUrl,
        public readonly array $comments,
        public readonly array $images,
        public readonly array $videos,
    ) {
    }
}

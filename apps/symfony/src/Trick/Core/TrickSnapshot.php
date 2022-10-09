<?php

declare(strict_types=1);

namespace App\Trick\Core;

use Symfony\Component\Uid\AbstractUid;

class TrickSnapshot
{
    /**
     * @param array<Image>   $images
     * @param array<Video>   $videos
     * @param array<Comment> $comments
     */
    public function __construct(
        public readonly AbstractUid $uuid,
        public readonly string $name,
        public readonly string $description,
        public readonly AbstractUid $categoryId,
        public readonly string $slug,
        public readonly Image $thumbnail,
        public readonly array $images,
        public readonly array $videos,
        public readonly array $comments,
    ) {
    }
}

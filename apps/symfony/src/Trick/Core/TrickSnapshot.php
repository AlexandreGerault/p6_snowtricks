<?php

declare(strict_types=1);

namespace App\Trick\Core;

use Symfony\Component\Uid\AbstractUid;

class TrickSnapshot
{
    /**
     * @param array<Image> $images
     * @param array<Video> $videos
     */
    public function __construct(
        public readonly string $uuid,
        public readonly string $name,
        public readonly string $description,
        public readonly AbstractUid $categoryId,
        public readonly string $slug,
        public readonly array $images,
        public readonly array $videos,
    ) {
    }
}

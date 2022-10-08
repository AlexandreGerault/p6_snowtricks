<?php

namespace App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments;

class TrickComment
{
    public function __construct(
        public readonly string $author,
        public readonly string $content,
        public readonly string $createdAt,
    ) {
    }
}

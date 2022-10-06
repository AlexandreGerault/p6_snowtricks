<?php

namespace App\Trick\Core\UseCases\Commands\CommentTrick;

class CommentTrickInputData
{
    public function __construct(
        public readonly string $trickId,
        public readonly string $authorId,
        public readonly string $commentContent,
    ) {
    }
}

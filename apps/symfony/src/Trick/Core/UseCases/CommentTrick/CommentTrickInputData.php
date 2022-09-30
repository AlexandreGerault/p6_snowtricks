<?php

namespace App\Trick\Core\UseCases\CommentTrick;

class CommentTrickInputData
{
    public function __construct(
        public readonly string $trickId,
        public readonly string $userId,
        public readonly string $commentContent,
    ) {
    }
}
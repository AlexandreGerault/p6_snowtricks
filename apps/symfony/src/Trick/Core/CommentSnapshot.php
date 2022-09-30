<?php

namespace App\Trick\Core;

use DateTimeImmutable;
use Symfony\Component\Uid\AbstractUid;

class CommentSnapshot
{
    public function __construct(
        public readonly AbstractUid $uuid,
        public readonly AbstractUid $userId,
        public readonly string $content,
        public readonly DateTimeImmutable $createdAt
    ) {
    }
}

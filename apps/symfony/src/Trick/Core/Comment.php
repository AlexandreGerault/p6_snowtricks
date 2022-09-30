<?php

namespace App\Trick\Core;

use DateTimeImmutable;
use Symfony\Component\Uid\AbstractUid;

class Comment
{
    public function __construct(
        private readonly AbstractUid $uuid,
        private readonly AbstractUid $userId,
        private readonly string $content,
        private readonly DateTimeImmutable $createdAt
    ) {
    }

    public function snapshot(): CommentSnapshot
    {
        return new CommentSnapshot(
            $this->uuid,
            $this->userId,
            $this->content,
            $this->createdAt
        );
    }
}
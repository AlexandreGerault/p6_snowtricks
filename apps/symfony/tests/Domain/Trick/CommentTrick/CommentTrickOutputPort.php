<?php

namespace App\Tests\Domain\Trick\CommentTrick;

use App\Trick\Core\Comment;
use App\Trick\Core\UseCases\Commands\CommentTrick\CommentTrickPresenter;
use PHPUnit\Framework\Assert;

class CommentTrickOutputPort implements CommentTrickPresenter
{
    private bool $trickCommented = false;
    private Comment $comment;

    public function trickCommented(Comment $comment): void
    {
        $this->comment = $comment;
        $this->trickCommented = true;
    }

    public function assertTrickCommented(): void
    {
        Assert::assertTrue($this->trickCommented);
    }

    public function comment(): Comment
    {
        return $this->comment;
    }
}

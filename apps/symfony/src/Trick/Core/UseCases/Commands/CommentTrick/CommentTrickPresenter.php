<?php

namespace App\Trick\Core\UseCases\Commands\CommentTrick;

use App\Trick\Core\Comment;

interface CommentTrickPresenter
{
    public function trickCommented(Comment $comment): void;
}

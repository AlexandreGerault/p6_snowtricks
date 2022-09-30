<?php

namespace App\Trick\Core\UseCases\CommentTrick;

use App\Trick\Core\Comment;

interface CommentTrickPresenter
{
    public function trickCommented(Comment $comment): void;
}
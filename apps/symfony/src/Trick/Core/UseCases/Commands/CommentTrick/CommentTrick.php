<?php

namespace App\Trick\Core\UseCases\Commands\CommentTrick;

use App\Shared\Dates\CurrentDateInterface;
use App\Trick\Core\Comment;
use App\Trick\Core\TrickGateway;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class CommentTrick
{
    public function __construct(private readonly TrickGateway $trickGateway, private readonly CurrentDateInterface $currentDate)
    {
    }

    public function executes(CommentTrickInputData $request, CommentTrickPresenter $presenter): void
    {
        $trick = $this->trickGateway->get(UuidV4::fromString($request->trickId));

        $comment = new Comment(
            uuid: UuidV4::v4(),
            userId: Uuid::fromString($request->authorId),
            content: $request->commentContent,
            createdAt: $this->currentDate->now()
        );

        $trick->comment($comment);

        $this->trickGateway->save($trick);

        $presenter->trickCommented($comment);
    }
}

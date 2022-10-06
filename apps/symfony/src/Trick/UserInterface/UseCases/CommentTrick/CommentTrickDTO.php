<?php

namespace App\Trick\UserInterface\UseCases\CommentTrick;

use App\Security\Infrastructure\Entity\User;
use App\Trick\Core\UseCases\Commands\CommentTrick\CommentTrickInputData;
use App\Trick\Infrastructure\Entity\Trick;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Validator\Constraints as Assert;

class CommentTrickDTO
{
    #[Assert\NotBlank]
    public string $message;

    public AbstractUid $trickId;
    public AbstractUid $authorId;

    public function __construct(Trick $trick, User $user)
    {
        $this->trickId = $trick->uuid();
        $this->authorId = $user->id();
    }

    public function toDomainRequest(): CommentTrickInputData
    {
        return new CommentTrickInputData(
            trickId: $this->trickId->toRfc4122(),
            authorId: $this->authorId->toRfc4122(),
            commentContent: $this->message,
        );
    }
}

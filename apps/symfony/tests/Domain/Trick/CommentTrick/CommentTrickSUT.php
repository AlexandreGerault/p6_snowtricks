<?php

namespace App\Tests\Domain\Trick\CommentTrick;

use App\Tests\Domain\Trick\Adapters\InMemoryTrickGateway;
use App\Tests\Helpers\Dates\FakeCurrentDate;
use App\Trick\Core\Trick;
use App\Trick\Core\UseCases\CommentTrick\CommentTrick;
use App\Trick\Core\UseCases\CommentTrick\CommentTrickInputData;
use Symfony\Component\Uid\UuidV4;

class CommentTrickSUT
{
    private CommentTrickOutputPort $output;
    private InMemoryTrickGateway $trickRepository;
    /** @var Trick[] */
    private array $tricks = [];
    private Trick $trick;
    private string $commentContent;
    private UuidV4 $userId;
    private FakeCurrentDate $currentDate;

    public static function new(): CommentTrickSUT
    {
        return new self();
    }

    public function run(): static
    {
        $this->currentDate = new FakeCurrentDate(new \DateTimeImmutable('2021-01-01 00:00:00'));
        $this->output = new CommentTrickOutputPort();
        $this->trickRepository = new InMemoryTrickGateway($this->tricks);

        $commentTrick = new CommentTrick($this->trickRepository, $this->currentDate);
        $input = new CommentTrickInputData(
            trickId: $this->trick->snapshot()->uuid->toRfc4122(),
            userId: $this->userId->toRfc4122(),
            commentContent: $this->commentContent
        );
        $commentTrick->executes($input, $this->output);

        return $this;
    }

    public function output(): CommentTrickOutputPort
    {
        return $this->output;
    }

    public function trickRepository(): InMemoryTrickGateway
    {
        return $this->trickRepository;
    }

    /** @param array<Trick> $tricks */
    public function withTricks(array $tricks): static
    {
        $this->tricks = $tricks;

        return $this;
    }

    public function onTrick(Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }

    public function withCommentContent(string $commentContent): static
    {
        $this->commentContent = $commentContent;
        
        return $this;
    }

    public function asUser(UuidV4 $userId): static
    {
        $this->userId = $userId;
        
        return $this;
    }

    public function currentDate(): \DateTimeImmutable
    {
        return $this->currentDate->now();
    }
}
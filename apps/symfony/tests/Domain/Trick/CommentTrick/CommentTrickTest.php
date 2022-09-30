<?php

namespace App\Tests\Domain\Trick\CommentTrick;

use App\Trick\Core\Image;
use App\Trick\Core\Trick;
use App\Trick\Core\Video;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class CommentTrickTest extends TestCase
{
    public function testAUserCanCommentATrick(): void
    {
        $trick = new Trick(
            UuidV4::fromString('4128c52f-6ccb-4db2-812c-c8f53b6571cb'),
            'Trick name',
            'Trick description',
            UuidV4::fromString('4128c52f-6ccb-4db2-812c-c8f53b6571cb'),
            'trick-name',
            [new Image('/', '')],
            [new Video('/')],
        );

        $sut = CommentTrickSUT::new()
            ->asUser(UuidV4::fromString('e3b7081b-0f67-40af-a725-4323a3a1ea3d'))
            ->onTrick($trick)
            ->withTricks([$trick])
            ->withCommentContent('Comment a trick')
            ->run();

        $trick = $sut->trickRepository()->get(UuidV4::fromString('4128c52f-6ccb-4db2-812c-c8f53b6571cb'));
        $comment = $trick->snapshot()->comments[0];

        $this->assertCount(1, $trick->snapshot()->comments);
        $sut->trickRepository()->assertTrickSaved();
        $sut->output()->assertTrickCommented();
        $this->assertEquals('Comment a trick', $comment->snapshot()->content);
        $this->assertEquals($sut->currentDate(), $comment->snapshot()->createdAt);
    }
}

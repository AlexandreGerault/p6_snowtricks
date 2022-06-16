<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\EditTrick;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV6;

class EditTrickTest extends TestCase
{
    public function testEditTrick(): void
    {
        $sut = EditTrickSUT::new()->run();

        $this->assertEquals('New name', $sut->output()->snapshot->name);
        $this->assertEquals('New description', $sut->output()->snapshot->description);
        $this->assertEquals("1ece6881-b8c6-6d6a-aa71-a9e76a603a2f", $sut->output()->snapshot->categoryId->toRfc4122());

        $this->assertCount(3, $sut->output()->snapshot->images);
        $this->assertEquals("97d9d9ec-db95-45e2-b501-67159e7825c0.jpg", $sut->output()->snapshot->images[1]->path);
        $this->assertEquals("539fcd0b-232c-4e78-a9bf-f923c33aea73.jpg", $sut->output()->snapshot->images[2]->path);

        $this->assertCount(1, $sut->output()->snapshot->videos);

        $sut->imageStorage()->assertImageExists('97d9d9ec-db95-45e2-b501-67159e7825c0.jpg');
        $sut->imageStorage()->assertImageExists('539fcd0b-232c-4e78-a9bf-f923c33aea73.jpg');
        $sut->imageStorage()->assertImageExists('fbbd53c1-1d1b-49ac-8e15-d1e0012742cc.jpg');

        $sut->imageStorage()->assertImageDoesNotExist('37b48eb3-3ed0-4019-a45f-88b90bc571a2.jpg');
        $sut->imageStorage()->assertImageDoesNotExist('df419614-60ac-44fb-8311-3925a399776e.jpg');

        $sut->repository()->assertTrickEdited();
    }

    public function testItCannotRemoveAllImages(): void
    {
        $this->expectException(InvalidArgumentException::class);
        EditTrickSUT::new()->withoutImages()->run();
    }

    public function testItCannotRemoveAllVideos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        EditTrickSUT::new()->withoutVideos()->run();
    }
}

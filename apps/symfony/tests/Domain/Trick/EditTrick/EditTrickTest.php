<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\EditTrick;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EditTrickTest extends TestCase
{
    public function testEditTrick(): void
    {
        $sut = EditTrickSUT::new()->run();

        $this->assertEquals('New name', $sut->output()->snapshot->name);
        $this->assertEquals('New description', $sut->output()->snapshot->description);
        $this->assertEquals("1ece6881-b8c6-6d6a-aa71-a9e76a603a2f", $sut->output()->snapshot->categoryId->toRfc4122());

        $this->assertCount(2, $sut->output()->snapshot->images);
        $this->assertEquals("1ece6a39-2b53-68ca-a3a3-3dd8fe5ea620.jpg", $sut->output()->snapshot->images[0]->path);
        $this->assertEquals("1ece5bf3-81aa-63a6-a118-fbv3diffs124.jpg", $sut->output()->snapshot->images[1]->path);

        $this->assertCount(1, $sut->output()->snapshot->videos);
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

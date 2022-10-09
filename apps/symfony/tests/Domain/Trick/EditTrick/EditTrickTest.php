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
        $this->assertEquals('7c012e6b-df13-481e-acdb-36f8250ad9c8', $sut->output()->snapshot->categoryId->toRfc4122());

        $this->assertCount(3, $sut->output()->snapshot->images);
        $this->assertEquals('d71d82c1-358a-430b-a8ac-90b5c3359f2b.jpg', $sut->output()->snapshot->images[1]->path);
        $this->assertEquals('911d7ab4-4658-476d-bfcc-d157882f44dd.jpg', $sut->output()->snapshot->images[2]->path);

        $this->assertCount(1, $sut->output()->snapshot->videos);

        $sut->imageStorage()->assertImageExists('d71d82c1-358a-430b-a8ac-90b5c3359f2b.jpg');
        $sut->imageStorage()->assertImageExists('911d7ab4-4658-476d-bfcc-d157882f44dd.jpg');
        $sut->imageStorage()->assertImageExists('359bec38-c06e-4892-8832-0e4780350151.jpg');

        $sut->imageStorage()->assertImageDoesNotExist('1c12f71c-7e00-4c34-adb9-cd8b55d1440a.jpg');
        $sut->imageStorage()->assertImageDoesNotExist('118139a9-8a86-4638-84d8-9cb61b0ace76.jpg');

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

<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\RegisterTrick;

use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class RegisterTrickTest extends TestCase
{
    /** @dataProvider provideValidInputs */
    public function testItCanRegisterATrick(string $name, string $description, string $category): void
    {
        $sut = RegisterTrickSUT::new()
            ->with([
                'name' => $name,
                'description' => $description,
                'category' => $category,
            ])
            ->withImages(2)
            ->withVideos(2)
            ->run();

        $this->assertCount(1, $sut->gateway()->findAll());
        $this->assertCount(2, $sut->imageStorage()->findAll());
        $this->assertEquals($name, $sut->output()->snapshot->name);
        $this->assertEquals($description, $sut->output()->snapshot->description);
        $this->assertEquals($category, $sut->output()->snapshot->categoryId);
    }

    public function testItCannotRegisterATrickWithoutImages(): void
    {
        $sut = RegisterTrickSUT::new()
            ->with([
                'name' => 'Trick without image',
                'description' => 'Trick without image',
                'category' => Uuid::v4()->toRfc4122(),
            ])
            ->withoutImages()
            ->withVideos(2)
            ->run();

        $this->assertCount(0, $sut->gateway()->findAll());
    }

    public function testItCannotRegisterATrickWithoutAVideo(): void
    {
        $sut = RegisterTrickSUT::new()
            ->with([
                'name' => 'Trick without video',
                'description' => 'Trick without video',
                'category' => Uuid::v4()->toRfc4122(),
            ])
            ->withImages(2)
            ->withoutVideos()
            ->run();

        $this->assertCount(0, $sut->gateway()->findAll());
    }

    public function provideValidInputs(): Generator
    {
        yield ['name' => 'test', 'description' => 'test', 'category' => Uuid::v4()->toRfc4122()];
        yield ['name' => 'test2', 'description' => 'test2', 'category' => Uuid::v4()->toRfc4122()];
    }
}

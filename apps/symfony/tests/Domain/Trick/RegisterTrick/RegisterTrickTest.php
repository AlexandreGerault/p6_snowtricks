<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\RegisterTrick;

use App\Trick\Core\UseCases\RegisterTrick\RegisterTrickResponse;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV6;

class RegisterTrickTest extends TestCase
{
    /** @dataProvider provideValidInputs */
    public function test_it_can_register_a_trick(string $name, string $description, string $category): void
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

    public function test_it_cannot_register_a_trick_without_images(): void
    {
        $sut = RegisterTrickSUT::new()
            ->with([
                'name' => "Trick without image",
                'description' => "Trick without image",
                'category' => UuidV6::generate(),
            ])
            ->withoutImages()
            ->withVideos(2)
            ->run();

        $this->assertCount(0, $sut->gateway()->findAll());
    }

    public function test_it_cannot_register_a_trick_without_a_video(): void
    {
        $sut = RegisterTrickSUT::new()
            ->with([
                'name' => "Trick without video",
                'description' => "Trick without video",
                'category' => UuidV6::generate(),
            ])
            ->withImages(2)
            ->withoutVideos()
            ->run();

        $this->assertCount(0, $sut->gateway()->findAll());
    }

    public function provideValidInputs(): Generator
    {
        yield ['name' => 'test', 'description' => 'test', 'category' => UuidV6::generate()];
        yield ['name' => 'test2', 'description' => 'test2', 'category' => UuidV6::generate()];
    }
}

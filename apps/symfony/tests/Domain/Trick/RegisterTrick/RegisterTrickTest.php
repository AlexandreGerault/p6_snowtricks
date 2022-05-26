<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\RegisterTrick;

use App\Trick\Core\UseCases\RegisterTrick\RegisterTrickResponse;
use Generator;
use PHPUnit\Framework\TestCase;

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
                'category' => "a0424572-1efe-4826-a5ac-bbda03a023f2",
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
                'category' => "a0424572-1efe-4826-a5ac-bbda03a023f2",
            ])
            ->withImages(2)
            ->withoutVideos()
            ->run();

        $this->assertCount(0, $sut->gateway()->findAll());
    }

    public function provideValidInputs(): Generator
    {
        yield ['name' => 'test', 'description' => 'test', 'category' => 'a0424572-1efe-4826-a5ac-bbda03a023f2'];
        yield ['name' => 'test2', 'description' => 'test2', 'category' => '585b23c8-e434-46ff-9462-08a9b900b436'];
    }
}

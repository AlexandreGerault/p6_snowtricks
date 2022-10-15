<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\DeleteTrick;

use App\Trick\Core\Image;
use App\Trick\Core\Trick;
use App\Trick\Core\Video;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class DeleteTrickTest extends TestCase
{
    public function testItDeletesATrick(): void
    {
        $sut = DeleteTrickSUT::new()
            ->id('8c75058e-72ce-43a3-bdc1-65274c3a2f38')
            ->withTricks([
                self::trickFixture('8c75058e-72ce-43a3-bdc1-65274c3a2f38'),
                self::trickFixture('44dde02b-7101-40d4-94ee-58f48cc2fd02'),
            ])
            ->run();

        $sut->repository()->assertTrickDoesNotExist('8c75058e-72ce-43a3-bdc1-65274c3a2f38');
        $sut->output()->assertTrickWasDeleted();
    }

    private static function trickFixture(string $id): Trick
    {
        return new Trick(
            UuidV4::fromString($id),
            'Old name',
            'Old description',
            new UuidV4('44dde02b-7101-40d4-94ee-58f48cc2fd02'),
            'old-name',
            new Image('44dde02b-7101-40d4-94ee-58f48cc2fd02.jpg', 'Thumbnail'),
            [
                new Image('1c12f71c-7e00-4c34-adb9-cd8b55d1440a.jpg', 'Old image'),
                new Image('118139a9-8a86-4638-84d8-9cb61b0ace76.jpg', 'Old image 2'),
                new Image('359bec38-c06e-4892-8832-0e4780350151.jpg', 'Same image'),
            ],
            [
                new Video('d524bdfc-d33f-4036-be06-ae785e40a68f.mp4'),
            ]
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\EditTrick;

use App\Tests\Domain\Trick\Adapters\InMemoryImageStorage;
use App\Tests\Domain\Trick\Adapters\InMemoryTrickGateway;
use App\Trick\Core\Image;
use App\Trick\Core\Trick;
use App\Trick\Core\UseCases\Commands\EditTrick\EditTrick;
use App\Trick\Core\UseCases\Commands\EditTrick\EditTrickInputData;
use App\Trick\Core\Video;
use Symfony\Component\Uid\UuidV4;

class EditTrickSUT
{
    private Trick $trickToEdit;
    private EditTrickTestOutputPort $testOutputPort;
    private InMemoryTrickGateway $inMemoryTrickGateway;
    private InMemoryImageStorage $imageStorage;

    /** @var array<array{path:string, alt:string}> */
    public array $images = [
        [
            'path' => 'd71d82c1-358a-430b-a8ac-90b5c3359f2b.jpg',
            'alt' => 'New image',
        ],
        [
            'path' => '911d7ab4-4658-476d-bfcc-d157882f44dd.jpg',
            'alt' => 'New image 2',
        ],
        [
            'path' => '359bec38-c06e-4892-8832-0e4780350151.jpg',
            'alt' => 'Same image',
        ],
    ];

    /** @var string[] */
    private array $videos = [
        '8c75058e-72ce-43a3-bdc1-65274c3a2f38.mp4',
    ];

    public function __construct()
    {
        $this->trickToEdit = new Trick(
            UuidV4::fromString('8c75058e-72ce-43a3-bdc1-65274c3a2f38'),
            'Old name',
            'Old description',
            new UuidV4('8c75058e-72ce-43a3-bdc1-65274c3a2f38'),
            'old-name',
            [
                new Image('1c12f71c-7e00-4c34-adb9-cd8b55d1440a.jpg', 'Old image'),
                new Image('118139a9-8a86-4638-84d8-9cb61b0ace76.jpg', 'Old image 2'),
                new Image('359bec38-c06e-4892-8832-0e4780350151.jpg', 'Same image'),
            ],
            [
                new Video('d524bdfc-d33f-4036-be06-ae785e40a68f.mp4'),
            ]
        );
        $this->imageStorage = new InMemoryImageStorage([
            '1c12f71c-7e00-4c34-adb9-cd8b55d1440a.jpg',
            '118139a9-8a86-4638-84d8-9cb61b0ace76.jpg',
            '359bec38-c06e-4892-8832-0e4780350151.jpg',
        ]);

        $this->testOutputPort = new EditTrickTestOutputPort();

        $this->inMemoryTrickGateway = new InMemoryTrickGateway([$this->trickToEdit]);
    }

    public static function new(): self
    {
        return new self();
    }

    public function run(): self
    {
        $request = new EditTrickInputData(
            '8c75058e-72ce-43a3-bdc1-65274c3a2f38',
            'New name',
            'New description',
            '7c012e6b-df13-481e-acdb-36f8250ad9c8',
            $this->images,
            $this->videos,
        );
        $editTrick = new EditTrick($this->inMemoryTrickGateway, $this->imageStorage);
        $editTrick->executes($request, $this->testOutputPort);

        return $this;
    }

    public function output(): EditTrickTestOutputPort
    {
        return $this->testOutputPort;
    }

    public function withoutImages(): self
    {
        $this->images = [];

        return $this;
    }

    public function withoutVideos(): self
    {
        $this->videos = [];

        return $this;
    }

    public function imageStorage(): InMemoryImageStorage
    {
        return $this->imageStorage;
    }

    public function repository(): InMemoryTrickGateway
    {
        return $this->inMemoryTrickGateway;
    }
}

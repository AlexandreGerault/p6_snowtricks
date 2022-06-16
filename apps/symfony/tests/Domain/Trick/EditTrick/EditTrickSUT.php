<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\EditTrick;

use App\Tests\Domain\Trick\Adapters\InMemoryImageStorage;
use App\Tests\Domain\Trick\Adapters\InMemoryTrickGateway;
use App\Trick\Core\Image;
use App\Trick\Core\Trick;
use App\Trick\Core\UseCases\EditTrick\EditTrick;
use App\Trick\Core\UseCases\EditTrick\EditTrickInputData;
use App\Trick\Core\Video;
use Symfony\Component\Uid\UuidV6;

class EditTrickSUT
{
    private Trick $trickToEdit;
    private EditTrickTestOutputPort $testOutputPort;
    private InMemoryTrickGateway $inMemoryTrickGateway;
    private InMemoryImageStorage $imageStorage;

    /** @var array<array{path:string, alt:string}> */
    public array $images = [
        [
            "path" => "97d9d9ec-db95-45e2-b501-67159e7825c0.jpg",
            "alt" => "New image",
        ],
        [
            "path" => "539fcd0b-232c-4e78-a9bf-f923c33aea73.jpg",
            "alt" => "New image 2",
        ],
        [
            "path" => "fbbd53c1-1d1b-49ac-8e15-d1e0012742cc.jpg",
            "alt" => "Same image",
        ]
    ];

    /** @var string[] */
    private array $videos = [
        '1ece5bf4-83aa-6ca6-ac18-fb03dfecd997.mp4',
    ];

    public function __construct()
    {
        $this->trickToEdit = new Trick(
            UuidV6::fromString('1ece5bf4-83aa-6ca6-ac18-fb03dfecd997'),
            'Old name',
            'Old description',
            new UuidV6('1ece5bf4-83aa-6ca6-ac18-fb03dfecd997'),
            'old-name',
            [
                new Image('37b48eb3-3ed0-4019-a45f-88b90bc571a2.jpg', 'Old image'),
                new Image('df419614-60ac-44fb-8311-3925a399776e.jpg', 'Old image 2'),
                new Image('fbbd53c1-1d1b-49ac-8e15-d1e0012742cc.jpg', 'Same image'),
            ],
            [
                new Video('1e37acb3-bc71-4d87-aa93-631e6ecd97a3.mp4'),
            ]
        );
        $this->imageStorage = new InMemoryImageStorage([
            '37b48eb3-3ed0-4019-a45f-88b90bc571a2.jpg',
            'df419614-60ac-44fb-8311-3925a399776e.jpg',
            'fbbd53c1-1d1b-49ac-8e15-d1e0012742cc.jpg',
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
            "1ece5bf4-83aa-6ca6-ac18-fb03dfecd997",
            "New name",
            "New description",
            "1ece6881-b8c6-6d6a-aa71-a9e76a603a2f",
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

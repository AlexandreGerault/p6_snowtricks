<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\EditTrick;

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

    /** @var array<array{path:string, alt:string}> */
    public array $images = [
        [
            "path" => "1ece6a39-2b53-68ca-a3a3-3dd8fe5ea620.jpg",
            "alt" => "New image",
        ],
        [
            "path" => "1ece5bf3-81aa-63a6-a118-fbv3diffs124.jpg",
            "alt" => "New image",
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
                new Image('1ece5bf4-83aa-6ca6-ac18-fb03dfecd997.jpg', 'Old image'),
                new Image('1ece5bf3-81aa-63a6-a118-fbv3diffs124.jpg', 'Old image 2'),
            ],
            [
                new Video('1ece5bf4-83aa-6ca6-ac18-fb03dfecd997.mp4'),
            ]
        );

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
        $editTrick = new EditTrick($this->inMemoryTrickGateway);
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
}

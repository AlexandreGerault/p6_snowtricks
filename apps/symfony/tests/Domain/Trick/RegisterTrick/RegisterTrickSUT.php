<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\RegisterTrick;

use App\Tests\Domain\Trick\Adapters\InMemoryImageStorage;
use App\Tests\Domain\Trick\Adapters\InMemoryTrickGateway;
use App\Trick\Core\ImageStorage;
use App\Trick\Core\TrickGateway;
use App\Trick\Core\UseCases\Commands\RegisterTrick\RegisterTrick;
use App\Trick\Core\UseCases\Commands\RegisterTrick\RegisterTrickInputData;

class RegisterTrickSUT
{
    private string $name = '';
    private string $description = '';
    private string $categoryId = '';

    /** @var array{path: string, alt: string} */
    private array $thumbnail = [];
    /** @var array{path: string, alt: string}[] */
    private array $images = [];
    /** @var array<string> */
    private array $videos = [];

    private RegisterTrickTestOutputPort $outputPort;

    private TrickGateway $gateway;
    private ImageStorage $imageStorage;

    public function __construct()
    {
    }

    public static function new(): static
    {
        return new self();
    }

    public function run(): static
    {
        $this->imageStorage = new InMemoryImageStorage();
        $this->gateway = new InMemoryTrickGateway();
        $request = new RegisterTrickInputData(
            $this->name,
            $this->description,
            $this->categoryId,
            $this->thumbnail,
            $this->images,
            $this->videos
        );
        $this->outputPort = new RegisterTrickTestOutputPort();

        $registerTrick = new RegisterTrick($this->gateway, $this->imageStorage);
        $registerTrick->executes($request, $this->outputPort);

        return $this;
    }

    public function with(array $array): static
    {
        $this->name = $array['name'];
        $this->description = $array['description'];
        $this->categoryId = $array['category'];

        return $this;
    }

    public function withImages(int $int): static
    {
        for ($i = 0; $i < $int; ++$i) {
            $this->images[] = ['path' => "public/storage/img/figure_{$i}.png", 'alt' => 'test'];
        }

        return $this;
    }

    public function withVideos(int $int): static
    {
        for ($i = 0; $i < $int; ++$i) {
            $this->videos[] = "https://www.youtube.com/watch?v=lCQigQcTMJ{$i}";
        }

        return $this;
    }

    public function output(): RegisterTrickTestOutputPort
    {
        return $this->outputPort;
    }

    public function gateway(): TrickGateway
    {
        return $this->gateway;
    }

    public function withoutImages(): static
    {
        $this->images = [];

        return $this;
    }

    public function withoutVideos(): static
    {
        $this->videos = [];

        return $this;
    }

    public function imageStorage(): ImageStorage
    {
        return $this->imageStorage;
    }

    public function withThumbnail(string $thumbnail, string $alt = 'test'): static
    {
        $this->thumbnail = ['path' => "public/storage/img/{$thumbnail}", 'alt' => $alt];

        return $this;
    }
}

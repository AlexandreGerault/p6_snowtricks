<?php

declare(strict_types=1);

namespace App\Tests\Domain\Trick\RegisterTrick;

use App\Tests\Domain\Trick\Adapters\InMemoryImageStorage;
use App\Tests\Domain\Trick\Adapters\InMemoryTrickGateway;
use App\Trick\Core\ImageStorage;
use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;
use App\Trick\Core\UseCases\RegisterTrick\RegisterTrick;
use App\Trick\Core\UseCases\RegisterTrick\RegisterTrickInputData;
use App\Trick\Core\UseCases\RegisterTrick\RegisterTrickResponse;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class RegisterTrickSUT
{
    private string $name = '';
    private string $description = '';
    private string $categoryId = '';

    private array $images = [];
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
        for ($i = 0; $i < $int; $i++) {
            $this->images[] = ['path' => "public/storage/img/figure_{$i}.png", 'alt' => "test"];
        }

        return $this;
    }

    public function withVideos(int $int): static
    {
        for ($i = 0; $i < $int; $i++) {
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
}

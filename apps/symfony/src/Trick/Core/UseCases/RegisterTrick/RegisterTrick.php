<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\RegisterTrick;

use App\Trick\Core\Image;
use App\Trick\Core\ImageStorage;
use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;
use App\Trick\Core\Video;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;

class RegisterTrick
{
    public function __construct(private readonly TrickGateway $gateway, private readonly ImageStorage $imageStorage)
    {
    }

    public function executes(RegisterTrickInputData $request, RegisterTrickOutputPort $outputPort): void
    {
        try {
            $images = array_map(function (array $image) {
                return new Image($this->imageStorage->save($image['path']), $image['alt']);
            }, $request->images);

            $videos = array_map(function (string $video) {
                return new Video($video);
            }, $request->videos);

            $trick = Trick::create(
                Uuid::v6(),
                $request->name,
                $request->description,
                UuidV6::fromString($request->categoryId),
                $images,
                $videos,
            );

            $this->gateway->save($trick);
        } catch (\Exception) {
            $outputPort->cannotCreateTrick();
            return;
        }

        $outputPort->trickCreated($trick->snapshot());
    }
}

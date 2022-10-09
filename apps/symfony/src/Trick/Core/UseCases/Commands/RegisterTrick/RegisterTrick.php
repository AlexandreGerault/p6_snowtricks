<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\Commands\RegisterTrick;

use App\Trick\Core\Image;
use App\Trick\Core\ImageStorage;
use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;
use App\Trick\Core\Video;
use Symfony\Component\Uid\Uuid;

class RegisterTrick
{
    public function __construct(private readonly TrickGateway $gateway, private readonly ImageStorage $imageStorage)
    {
    }

    public function executes(RegisterTrickInputData $request, RegisterTrickOutputPort $outputPort): void
    {
        try {
            $thumbnail = new Image($this->imageStorage->save($request->thumbnail['path']), $request->thumbnail['alt']);

            $images = array_map(function (array $image) {
                return new Image($this->imageStorage->save($image['path']), $image['alt']);
            }, $request->images);

            $videos = array_map(function (string $video) {
                return new Video($video);
            }, $request->videos);

            $trick = Trick::create(
                Uuid::v4(),
                $request->name,
                $request->description,
                Uuid::fromString($request->categoryId),
                $thumbnail,
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

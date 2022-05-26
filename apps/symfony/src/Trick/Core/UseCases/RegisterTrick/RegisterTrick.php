<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\RegisterTrick;

use App\Trick\Core\Image;
use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;
use App\Trick\Core\Video;
use Symfony\Component\Uid\UuidV4;

class RegisterTrick
{
    public function __construct(private readonly TrickGateway $gateway)
    {
    }

    public function executes(RegisterTrickInputData $request, RegisterTrickOutputPort $outputPort): void
    {
        try {
            $images = array_map(function (array $image) {
                return new Image($image['path'], $image['description']);
            }, $request->images);

            $videos = array_map(function (array $video) {
                return new Video($video['url']);
            }, $request->videos);

            $trick = Trick::create(
                $request->name,
                $request->description,
                UuidV4::fromString($request->categoryId),
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

<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\EditTrick;

use App\Trick\Core\Image;
use App\Trick\Core\ImageStorage;
use App\Trick\Core\TrickGateway;
use App\Trick\Core\Video;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class EditTrick
{
    public function __construct(private readonly TrickGateway $trickGateway, private readonly ImageStorage $imageStorage)
    {
    }

    public function executes(EditTrickInputData $request, EditTrickPresenter $presenter): void
    {
        $trick = $this->trickGateway->get(UuidV4::fromString($request->trickId));
        $snapshot = $trick->snapshot();

        $requestImages = array_map(fn (array $image) => new Image($image['path'], $image['alt']), $request->images);

        $imagesToSave = array_diff($requestImages, $snapshot->images);
        $newImages = array_map(function (Image $image) {
            return new Image(
                $this->imageStorage->save($image->path),
                $image->description
            );
        }, $imagesToSave);

        $oldImages = array_diff($snapshot->images, $requestImages);
        foreach ($oldImages as $image) {
            $this->imageStorage->delete($image->path);
        }

        $unchangedImages = array_intersect($requestImages, $snapshot->images);

        $trick->rename($request->name);
        $trick->changeDescription($request->description);
        $trick->changeCategory(Uuid::fromString($request->categoryId));
        $trick->updateImages(array_merge($unchangedImages, $newImages));
        $trick->updateVideos(array_map(fn (string $video) => new Video($video), $request->videos));

        $this->trickGateway->save($trick);

        $presenter->trickEdited($trick);
    }
}

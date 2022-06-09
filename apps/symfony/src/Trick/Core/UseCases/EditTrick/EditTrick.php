<?php

declare(strict_types=1);

namespace App\Trick\Core\UseCases\EditTrick;

use App\Trick\Core\Image;
use App\Trick\Core\TrickGateway;
use App\Trick\Core\Video;
use Symfony\Component\Uid\UuidV6;

class EditTrick
{
    public function __construct(private readonly TrickGateway $trickGateway)
    {
    }

    public function executes(EditTrickInputData $request, EditTrickPresenter $presenter): void
    {
        $trick = $this->trickGateway->get(UuidV6::fromString($request->trickId));

        $trick->rename($request->name);
        $trick->changeDescription($request->description);
        $trick->changeCategory(UuidV6::fromString($request->categoryId));
        $trick->updateImages(array_map(fn (array $image) => new Image($image['path'], $image['alt']), $request->images));
        $trick->updateVideos(array_map(fn (string $video) => new Video($video), $request->videos));

        $presenter->trickEdited($trick);
    }
}

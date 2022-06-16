<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\EditTrick;

use App\Trick\Core\UseCases\EditTrick\EditTrickInputData;
use App\Trick\Infrastructure\Entity\Category;
use App\Trick\Infrastructure\Entity\Image;
use App\Trick\Infrastructure\Entity\Trick;
use App\Trick\Infrastructure\Entity\Video;
use App\Trick\UserInterface\Form\Type\ImageDTO;
use App\Trick\UserInterface\Form\Type\VideoDTO;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Validator\Constraints as Assert;

class EditTrickDTO
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $description;

    #[Assert\NotBlank]
    public Category $category;

    /** @var ImageDTO[] */
    #[Assert\Valid]
    #[Assert\Type(type: "array")]
    #[Assert\All(constraints: [new Assert\Type(type: ImageDTO::class)])]
    #[Assert\Count(min: 1)]
    public array $images;


    /** @var VideoDTO[] */
    #[Assert\Valid]
    #[Assert\Type(type: "array")]
    #[Assert\All(constraints: [new Assert\Type(type: VideoDTO::class)])]
    #[Assert\Count(min: 1)]
    public array $videos;

    public AbstractUid $trickId;

    public function __construct(Trick $trick)
    {
        $this->trickId = $trick->uuid();
        $this->name = $trick->name();
        $this->description = $trick->description();
        $this->category = $trick->category();
        $this->images = array_map(fn(Image $image) => new ImageDTO($image), $trick->images()->toArray());
        $this->videos = array_map(fn(Video $video) => new VideoDTO($video), $trick->videos()->toArray());
    }

    public function toDomainRequest(): EditTrickInputData
    {
        return new EditTrickInputData(
            $this->trickId->toRfc4122(),
            $this->name,
            $this->description,
            $this->category->uuid()->toRfc4122(),
            array_map(fn(ImageDTO $image) => $image->toDomain(), $this->images),
            array_map(fn(VideoDTO $video) => $video->toDomain(), $this->videos)
        );
    }
}

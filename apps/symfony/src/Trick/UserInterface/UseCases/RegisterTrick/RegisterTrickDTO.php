<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\RegisterTrick;

use App\Shared\Constraints\UniqueField;
use App\Trick\Core\UseCases\Commands\RegisterTrick\RegisterTrickInputData;
use App\Trick\Infrastructure\Entity\Category;
use App\Trick\Infrastructure\Entity\Thumbnail;
use App\Trick\UserInterface\Form\Type\ImageDTO;
use App\Trick\UserInterface\Form\Type\VideoDTO;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterTrickDTO
{
    #[Assert\NotBlank]
    #[UniqueField(options: ['table' => 'tricks', 'field' => 'name', 'fieldName' => 'nom de figure'])]
    public string $name;

    #[Assert\NotBlank]
    public string $description;

    #[Assert\NotBlank]
    public Category $category;

    #[Assert\Valid]
    #[Assert\Type(type: ImageDTO::class)]
    public ImageDTO $thumbnail;

    /** @var ImageDTO[] */
    #[Assert\Valid]
    #[Assert\Type(type: 'array')]
    #[Assert\All(constraints: [new Assert\Type(type: ImageDTO::class)])]
    #[Assert\Count(min: 1)]
    public array $images;

    /** @var VideoDTO[] */
    #[Assert\Valid]
    #[Assert\Type(type: 'array')]
    #[Assert\All(constraints: [new Assert\Type(type: VideoDTO::class)])]
    #[Assert\Count(min: 1)]
    public array $videos;

    public function toDomainRequest(): RegisterTrickInputData
    {
        return new RegisterTrickInputData(
            $this->name,
            $this->description,
            $this->category->uuid()->toRfc4122(),
            $this->thumbnail->toDomain(),
            array_map(fn (ImageDTO $image) => $image->toDomain(), $this->images),
            array_map(fn (VideoDTO $video) => $video->toDomain(), $this->videos)
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\RegisterTrick;

use App\Trick\Core\UseCases\RegisterTrick\RegisterTrickInputData;
use App\Trick\Infrastructure\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterTrickDTO
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $description;

    #[Assert\NotBlank]
    public Category $category;

    #[Assert\Valid]
    #[Assert\Type(type: "array")]
    #[Assert\All(constraints: [new Assert\Type(type: ImageDTO::class)])]
    #[Assert\Count(min: 1)]
    /** @var array<ImageDTO> */
    public array $images;

    #[Assert\All(constraints: [new Assert\Url()])]
    #[Assert\Count(min: 1)]
    /** @var array<string> */
    public array $videos;

    public function toDomainRequest(): RegisterTrickInputData
    {
        return new RegisterTrickInputData(
            $this->name,
            $this->description,
            $this->category->uuid()->toRfc4122(),
            array_map(fn(ImageDTO $image) => $image->toDomain(), $this->images),
            $this->videos
        );
    }
}

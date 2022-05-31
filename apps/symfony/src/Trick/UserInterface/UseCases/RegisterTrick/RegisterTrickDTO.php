<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\RegisterTrick;

use App\Shared\Constraints\UniqueField;
use App\Trick\Core\UseCases\RegisterTrick\RegisterTrickInputData;
use App\Trick\Infrastructure\Entity\Category;
use App\Trick\UserInterface\Type\ImageDTO;
use App\Trick\UserInterface\Type\VideoDTO;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterTrickDTO
{
    #[Assert\NotBlank]
    #[UniqueField(options: ['table' => 'tricks', 'field' => 'name', 'fieldName' => "nom de figure"])]
    public string $name;

    #[Assert\NotBlank]
    public string $description;

    #[Assert\NotBlank]
    public Category $category;

    #[Assert\Valid]
    #[Assert\Type(type: "array")]
    #[Assert\All(constraints: [new Assert\Type(type: ImageDTO::class)])]
    #[Assert\Count(min: 1)]
    public array $images;


    #[Assert\Valid]
    #[Assert\Type(type: "array")]
    #[Assert\All(constraints: [new Assert\Type(type: VideoDTO::class)])]
    #[Assert\Count(min: 1)]
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

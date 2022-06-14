<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\Form\Type;

use App\Trick\Infrastructure\Entity\Image;
use App\Trick\UserInterface\Form\Constraints\NullableImageWithDefaultPath;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[NullableImageWithDefaultPath]
class ImageDTO
{
    #[Assert\Image]
    public ?UploadedFile $image;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $alt;

    public ?string $path;

    public function __construct(?Image $image = null)
    {
        if ($image) {
            $this->path = $image->path();
            $this->alt = $image->alt();
        }
    }

    /** @return array{alt: string, path: string} */
    public function toDomain(): array
    {
        return [
            'path' => is_null($this->image) ? $this->path : $this->image->getRealPath(),
            'alt' => $this->alt,
        ];
    }
}

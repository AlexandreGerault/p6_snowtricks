<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\RegisterTrick;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ImageDTO
{
    #[Assert\Image]
    public UploadedFile $image;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $alt;

    /** @return array{alt: string, path: string} */
    public function toDomain(): array
    {
        return [
            'path' => $this->image->getRealPath(),
            'alt' => $this->alt,
        ];
    }
}

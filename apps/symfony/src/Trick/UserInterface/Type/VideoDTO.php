<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\Type;

use App\Trick\Infrastructure\Entity\Video;
use Symfony\Component\Validator\Constraints as Assert;

class VideoDTO
{
    #[Assert\NotBlank]
    #[Assert\Url]
    public string $url;

    public function __construct(?Video $video = null)
    {
        if ($video) {
            $this->url = $video->url();
        }
    }

    public function toDomain(): string
    {
        return $this->url;
    }
}

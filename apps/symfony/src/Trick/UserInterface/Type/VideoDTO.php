<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\Type;

use Symfony\Component\Validator\Constraints as Assert;

class VideoDTO
{
    #[Assert\NotBlank]
    #[Assert\Url]
    public string $url;
}

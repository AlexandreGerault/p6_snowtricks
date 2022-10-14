<?php

namespace App\Security\UserInterface\UseCases\AskPasswordReset;

use Symfony\Component\Validator\Constraints as Assert;

class AskPasswordResetDTO
{
    #[Assert\NotBlank]
    public string $username;
}

<?php

declare(strict_types=1);

namespace App\Security\UserInterface\UseCases\Register;

use App\Security\Core\UseCases\Register\RegisterInputData;
use App\Shared\Constraints\UniqueField;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterDTO
{
    #[Assert\NotBlank]
    #[UniqueField(options: ['table' => 'users', 'field' => 'username', 'fieldName' => "nom d'utilisateur"])]
    public string $username;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[UniqueField(options: ['table' => 'users', 'field' => 'email', 'fieldName' => 'adresse mail'])]
    public string $email;

    #[Assert\NotBlank]
    public string $password;

    public function toDomainRequest(): RegisterInputData
    {
        return new RegisterInputData($this->username, $this->email, $this->password);
    }
}

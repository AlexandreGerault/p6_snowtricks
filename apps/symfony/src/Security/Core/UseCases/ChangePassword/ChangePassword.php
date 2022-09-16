<?php

namespace App\Security\Core\UseCases\ChangePassword;

use App\Security\Core\PasswordHasher;
use App\Security\Core\PlainPassword;
use App\Security\Core\ResetPasswordToken;
use App\Security\Core\UserRepository;

class ChangePassword
{
    public function __construct(private readonly UserRepository $userRepository, private readonly PasswordHasher $passwordHasher)
    {
    }

    public function executes(ChangePasswordInputData $input, ChangePasswordPresenter $presenter): void
    {
        $resetPasswordToken = new ResetPasswordToken($input->token);

        $user = $this->userRepository->findByPasswordResetToken($resetPasswordToken);

        if (is_null($user)) {
            $presenter->userNotFound();

            return;
        }

        $newHashedPassword = $this->passwordHasher->hash(new PlainPassword($input->newPassword));
        $user->changePassword($newHashedPassword);

        $this->userRepository->save($user);

        $presenter->passwordChanged();
    }
}

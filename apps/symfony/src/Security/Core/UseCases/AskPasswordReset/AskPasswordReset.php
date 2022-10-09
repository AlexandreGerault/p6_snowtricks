<?php

namespace App\Security\Core\UseCases\AskPasswordReset;

use App\Security\Core\NotificationGateway;
use App\Security\Core\PasswordResetTokenGenerator;
use App\Security\Core\UserRepository;

class AskPasswordReset
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordResetTokenGenerator $passwordResetTokenGenerator,
        private readonly NotificationGateway $notificationGateway,
    ) {
    }

    public function executes(AskPasswordResetInputData $input, AskPasswordResetPresenter $presenter): void
    {
        $user = $this->userRepository->findByEmail($input->email);

        if (is_null($user)) {
            $presenter->userNotFound();

            return;
        }

        $passwordResetToken = $this->passwordResetTokenGenerator->generate();
        $user->changePasswordResetToken($passwordResetToken);

        $this->userRepository->save($user);

        $presenter->resetTokenCreated();
        $this->notificationGateway->notifyPasswordResetTokenCreated($user);
    }
}

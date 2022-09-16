<?php

declare(strict_types=1);

namespace App\Security\Infrastructure;

use App\Security\Core\NotificationGateway;
use App\Security\Core\User;
use App\Security\Core\UserSnapshot;
use App\Security\Infrastructure\Repository\UserRepository;
use App\Security\UserInterface\UseCases\AskPasswordReset\AskPasswordRequestMail;
use App\Security\UserInterface\UseCases\Register\RegisterConfirmationLinkMail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class Notifications implements NotificationGateway
{
    public function __construct(
        private readonly MailerInterface  $mailer,
        private LoginLinkHandlerInterface $loginLinkHandler,
        private UserRepository            $repository
    )
    {
    }

    /** @throws TransportExceptionInterface */
    public function notifyAccountCreated(UserSnapshot $user): void
    {
        $doctrineUser = $this->repository->findOneBy(['email' => $user->email]);

        if (is_null($doctrineUser)) {
            throw new \RuntimeException('User not found');
        }

        $confirmLink = $this->loginLinkHandler->createLoginLink($doctrineUser);

        $mail = new RegisterConfirmationLinkMail($confirmLink->getUrl(), $user->email);
        $this->mailer->send($mail);
    }

    /** @throws TransportExceptionInterface */
    public function notifyPasswordResetTokenCreated(User $user): void
    {
        $snapshot = $user->snapshot();

        if (!is_string($snapshot->passwordResetToken?->token)) {
            throw new \RuntimeException('Token is not a string');
        }

        $token = $snapshot->passwordResetToken->token;

        $mail = new AskPasswordRequestMail($token, $snapshot->email);
        $this->mailer->send($mail);
    }
}
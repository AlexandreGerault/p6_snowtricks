<?php

declare(strict_types=1);

namespace App\Security\Infrastructure;

use App\Security\Core\NotificationGateway;
use App\Security\Core\UserSnapshot;
use App\Security\Infrastructure\Repository\UserRepository;
use App\Security\UserInterface\UseCases\Register\RegisterConfirmationLinkMail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class Notifications implements NotificationGateway
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private LoginLinkHandlerInterface $loginLinkHandler,
        private UserRepository $repository
    ) {
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
}
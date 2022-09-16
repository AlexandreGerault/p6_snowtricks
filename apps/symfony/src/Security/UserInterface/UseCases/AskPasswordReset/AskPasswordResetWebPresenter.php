<?php

namespace App\Security\UserInterface\UseCases\AskPasswordReset;

use App\Security\Core\UseCases\AskPasswordReset\AskPasswordResetPresenter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AskPasswordResetWebPresenter implements AskPasswordResetPresenter
{
    private Response $response;

    public function __construct(
        private readonly UrlGeneratorInterface $generator,
        private readonly SessionBagInterface   $flashBag
    )
    {
    }

    public function resetTokenCreated(): void
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            $this->flashBag->add('success', "Un email de réinitialisation de mot de passe vous a été envoyé !");
        }
        $this->response = new RedirectResponse($this->generator->generate('homepage'));
    }

    public function response(): Response
    {
        return $this->response;
    }

    public function userNotFound(): void
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            $this->flashBag->add('success', "Un email de réinitialisation de mot de passe vous a été envoyé !");
        }
        $this->response = new RedirectResponse($this->generator->generate('homepage'));
    }
}
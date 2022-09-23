<?php

namespace App\Security\UserInterface\UseCases\ActivateAccount;

use App\Security\Core\UseCases\ActivateAccount\ActivateAccountPresenter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ActivateAccountWebPresenter implements ActivateAccountPresenter
{
    private Response $response;

    public function __construct(private readonly UrlGeneratorInterface $generator, private readonly SessionBagInterface $flashBag)
    {
    }

    public function userHasBeenActivated(): void
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            $this->flashBag->add('success', 'Votre compte a bien été activé !');
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
            $this->flashBag->add('error', "Aucun compte ne correspond à ce jeton d'activation !");
        }
        $this->response = new RedirectResponse($this->generator->generate('homepage'));
    }
}

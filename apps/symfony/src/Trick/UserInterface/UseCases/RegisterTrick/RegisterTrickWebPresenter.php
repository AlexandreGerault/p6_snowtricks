<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\RegisterTrick;

use App\Trick\Core\TrickSnapshot;
use App\Trick\Core\UseCases\RegisterTrick\RegisterTrickOutputPort;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegisterTrickWebPresenter implements RegisterTrickOutputPort
{
    private Response $response;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly SessionBagInterface $flashBag
    ) {
    }

    public function trickCreated(TrickSnapshot $trick): void
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            $this->flashBag->add('success', 'La figure a bien été créée');
        }

        $this->response = new RedirectResponse($this->urlGenerator->generate('homepage'));
    }

    /** @codeCoverageIgnore  */
    public function cannotCreateTrick(): void
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            $this->flashBag->add('error', "La figure n'a pas pu être créée");
        }

        $this->response = new RedirectResponse($this->urlGenerator->generate('register_trick'));
    }

    public function response(): Response
    {
        return $this->response;
    }
}

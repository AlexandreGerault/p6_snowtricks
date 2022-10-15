<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\DeleteTrick;

use App\Trick\Core\UseCases\Commands\DeleteTrick\DeleteTrickPresenter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DeleteTrickWebPresenter implements DeleteTrickPresenter
{
    private Response $response;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly SessionBagInterface $flashBag
    ) {
    }

    public function trickDeleted(): void
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            $this->flashBag->add('success', 'La figure a bien été supprimée.');
        }

        $url = $this->urlGenerator->generate('homepage');
        $this->response = new RedirectResponse($url);
    }

    public function trickNotFound(): void
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            $this->flashBag->add('error', 'La figure n\a pas pu être supprimée.');
        }

        $url = $this->urlGenerator->generate('homepage');
        $this->response = new RedirectResponse($url);
    }

    public function response(): Response
    {
        return $this->response;
    }
}

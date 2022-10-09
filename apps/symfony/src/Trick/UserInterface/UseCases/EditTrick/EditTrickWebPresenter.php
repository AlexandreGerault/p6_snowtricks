<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\EditTrick;

use App\Trick\Core\Trick;
use App\Trick\Core\UseCases\Commands\EditTrick\EditTrickPresenter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EditTrickWebPresenter implements EditTrickPresenter
{
    private Response $response;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly SessionBagInterface $flashBag
    ) {
    }

    public function trickEdited(Trick $trick): void
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            $this->flashBag->add('success', 'La figure a bien été modifiée');
        }

        $this->response = new RedirectResponse($this->urlGenerator->generate('homepage'));
    }

    public function response(): Response
    {
        return $this->response;
    }
}

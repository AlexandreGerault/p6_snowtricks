<?php

namespace App\Trick\UserInterface\UseCases\CommentTrick;

use App\Trick\Core\Comment;
use App\Trick\Core\UseCases\Commands\CommentTrick\CommentTrickPresenter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;

class CommentTrickWebPresenter implements CommentTrickPresenter
{
    private Response $response;

    public function __construct(
        private readonly string $url,
        private readonly SessionBagInterface $flashBag
    ) {
    }

    public function trickCommented(Comment $comment): void
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            $this->flashBag->add('success', 'Votre commentaire a bien été ajouté');
        }

        $this->response = new RedirectResponse($this->url);
    }

    public function response(): Response
    {
        return $this->response;
    }
}

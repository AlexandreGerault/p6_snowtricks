<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\EditTrick;

use App\Trick\Core\Trick;
use App\Trick\Core\UseCases\EditTrick\EditTrickPresenter;
use Symfony\Component\HttpFoundation\Response;

class EditTrickWebPresenter implements EditTrickPresenter
{
    private Response $response;

    /**
     * @param $generator
     * @param $getBag
     */
    public function __construct($generator, $getBag)
    {
    }

    public function trickEdited(Trick $trick): void
    {
        // TODO: Implement trickEdited() method.
    }

    public function response(): Response
    {
        return $this->response;
    }
}

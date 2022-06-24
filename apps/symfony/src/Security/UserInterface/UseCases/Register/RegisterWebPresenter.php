<?php

namespace App\Security\UserInterface\UseCases\Register;

use App\Security\Core\UseCases\Register\RegisterPresenter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterWebPresenter implements RegisterPresenter
{
    private Response $response;

    public function userCreated(): void
    {
        $this->response = new RedirectResponse("/");
    }

    public function emailAlreadyInUse(): void
    {
        $this->response = new RedirectResponse("/");
    }

    public function response(): Response
    {
        return $this->response;
    }
}
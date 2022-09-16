<?php

namespace App\Security\UserInterface\UseCases\ChangePassword;

use App\Security\Core\UseCases\ChangePassword\ChangePasswordPresenter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ChangePasswordWebPresenter implements ChangePasswordPresenter
{
    private Response $response;

    public function __construct(private readonly UrlGeneratorInterface $generator)
    {
    }

    public function userNotFound(): void
    {
        dump("User not found");
    }

    public function passwordChanged(): void
    {
        $this->response = new RedirectResponse($this->generator->generate('homepage'));
    }

    public function response(): Response
    {
        return $this->response;
    }
}
<?php

declare(strict_types=1);

namespace App\Security\Register;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route(path: '/inscription')]
    public function __invoke(): Response
    {
        return $this->render('security/register.html.twig');
    }
}

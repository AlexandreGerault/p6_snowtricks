<?php

declare(strict_types=1);

namespace App\Security\UserInterface\UseCases\ActivateAccount;

use App\Security\Core\UseCases\ActivateAccount\ActivateAccount;
use App\Security\Core\UseCases\ActivateAccount\ActivateAccountInputData;
use App\Security\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ActivateAccountController extends AbstractController
{
    public function __construct(private readonly ActivateAccount $activateAccount, private readonly UrlGeneratorInterface $generator)
    {
    }

    #[Route(path: '/confirmer', name: 'app_activate_account')]
    public function __invoke(Request $request, UserRepository $userRepository, EntityManagerInterface $em, RequestStack $requestStack): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }

        $token = $request->get('token');

        if (!is_string($token)) {
            return $this->redirectToRoute('app_login');
        }

        $input = new ActivateAccountInputData($token);
        $presenter = new ActivateAccountWebPresenter(
            $this->generator,
            $requestStack->getSession()->getBag('flashes')
        );
        $this->activateAccount->executes($input, $presenter);

        return $presenter->response();
    }
}

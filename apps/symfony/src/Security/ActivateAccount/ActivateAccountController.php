<?php

declare(strict_types=1);

namespace App\Security\ActivateAccount;

use App\Security\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActivateAccountController extends AbstractController
{
    #[Route(path: "/confirmer", name: 'app_activate_account')]
    public function __invoke(Request $request, UserRepository $userRepository, EntityManagerInterface $em): RedirectResponse
    {
        $user = $userRepository->findByActivationToken($request->get('token'));

        if (!$user) {
            $this->addFlash("error", "Aucun compte ne correspond à ce jeton d'activation !");

            return $this->redirect('/');
        }

        $user->activate();

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Votre compte a bien été activé !');

        return $this->redirect('/');
    }
}

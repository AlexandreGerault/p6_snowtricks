<?php

declare(strict_types=1);

namespace App\Security\UserInterface\UseCases\ActivateAccount;

use App\Security\Infrastructure\Entity\ActivationToken;
use App\Security\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActivateAccountController extends AbstractController
{
    #[Route(path: '/confirmer', name: 'app_activate_account')]
    public function __invoke(Request $request, UserRepository $userRepository, EntityManagerInterface $em): RedirectResponse
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }

        $token = $request->get('token');

        if (!is_string($token)) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->findByActivationToken($token);

        if (!$user) {
            $this->addFlash('error', "Aucun compte ne correspond à ce jeton d'activation !");

            return $this->redirect('/');
        }

        $user->activate();

        $activationToken = $em->getRepository(ActivationToken::class)->findOneBy(compact('token'));
        if ($activationToken) {
            $em->remove($activationToken);
        }
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Votre compte a bien été activé !');

        return $this->redirect('/');
    }
}

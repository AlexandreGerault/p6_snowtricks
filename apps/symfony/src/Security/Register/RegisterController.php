<?php

declare(strict_types=1);

namespace App\Security\Register;

use App\Security\Entity\User;
use App\Security\Entity\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly UserFactory $factory,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/inscription')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(RegisterType::class, new RegisterFormModel());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var RegisterFormModel $data */
            $data = $form->getData();

            $user = $this->factory->create($data->username, $data->email, $data->password);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirect('/inscription');
        }

        return $this->render('security/register.html.twig', ['form' => $form->createView()]);
    }
}

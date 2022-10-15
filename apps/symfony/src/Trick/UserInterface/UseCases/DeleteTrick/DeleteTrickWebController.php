<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\DeleteTrick;

use App\Trick\Core\UseCases\Commands\DeleteTrick\DeleteTrick;
use App\Trick\Core\UseCases\Commands\DeleteTrick\DeleteTrickInputData;
use App\Trick\Infrastructure\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DeleteTrickWebController extends AbstractController
{
    public function __construct(private readonly DeleteTrick $deleteTrick, private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    #[Route('/figure/supprimer/{id}', name: 'delete_trick', methods: ['POST'])]
    public function __invoke(string $id, RequestStack $requestStack): Response
    {
        $input = new DeleteTrickInputData(trickId: $id);
        $output = new DeleteTrickWebPresenter(
            $this->urlGenerator,
            $requestStack->getSession()->getBag('flashes')
        );

        $this->deleteTrick->executes($input, $output);

        return $output->response();
    }
}

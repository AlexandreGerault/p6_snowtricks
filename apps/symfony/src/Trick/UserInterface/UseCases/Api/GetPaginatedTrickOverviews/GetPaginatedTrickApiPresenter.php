<?php

namespace App\Trick\UserInterface\UseCases\Api\GetPaginatedTrickOverviews;

use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\GetPaginatedTrickOverviewsPresenter;
use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\PaginatedTrickOverviewsResult;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GetPaginatedTrickApiPresenter implements GetPaginatedTrickOverviewsPresenter
{
    private Response $response;

    public function __construct(private readonly TrickOverviewResourceFactory $resourceFactory, private readonly SerializerInterface $serializer)
    {
    }

    public function presents(PaginatedTrickOverviewsResult $trickOverviews): void
    {
        $trickOverviewResources = $this->resourceFactory->createCollection($trickOverviews->trickOverviews);
        $json = $this->serializer->serialize($trickOverviewResources, 'json');

        $this->response = new JsonResponse($json, 200, [], true);
    }

    public function response(): Response
    {
        return $this->response;
    }
}

<?php

namespace App\Trick\Infrastructure\Queries;

use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\GetPaginatedTrickOverviewsQuery as GetPaginatedTrickOverviewsQueryInterface;
use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\PaginatedTrickOverviewsResult;
use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\TrickOverview;
use App\Trick\Infrastructure\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;

class GetPaginatedTrickOverviewsQuery implements GetPaginatedTrickOverviewsQueryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function run(int $limit, int $offset): PaginatedTrickOverviewsResult
    {
        $tricks = $this->entityManager->createQueryBuilder()
            ->select(
                't.uuid',
                't.name',
                't.slug',
                'c1.name AS categoryName',
                'i.path AS thumbnailUrl',
                'COUNT(c.uuid) AS commentsCount'
            )
            ->from(Trick::class, 't')
            ->innerJoin('t.category', 'c1')
            ->innerJoin('t.thumbnail', 'i')
            ->leftJoin('t.comments', 'c')
            ->setMaxResults($limit)
            ->setFirstResult(($offset - 1) * $limit)
            ->orderBy('t.createdAt', 'DESC')
            ->orderBy('t.name', 'ASC')
            ->groupBy('t.uuid')
            ->getQuery()
            ->getResult();

        $overviews = array_reduce(
            $tricks,
            function (array $carry, array $trick) {
                if (in_array($trick['name'], array_column($carry, 'name'))) {
                    return $carry;
                }

                $carry[] = new TrickOverview(
                    id: $trick['uuid']->toRfc4122(),
                    name: $trick['name'],
                    slug: $trick['slug'],
                    categoryName: $trick['categoryName'],
                    thumbnailUrl: $trick['thumbnailUrl'],
                    commentsCount: 0,
                );

                return $carry;
            },
            []
        );

        $totalOverviews = $this->entityManager->getConnection()->executeQuery('SELECT COUNT(*) FROM tricks')->fetchOne();

        return new PaginatedTrickOverviewsResult(
            total: $totalOverviews / $limit,
            perPage: $limit,
            page: $offset,
            trickOverviews: $overviews
        );
    }
}

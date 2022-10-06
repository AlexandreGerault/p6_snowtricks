<?php

namespace App\Trick\Infrastructure\Queries;

use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\GetPaginatedTrickOverviewsQuery as GetPaginatedTrickOverviewsQueryInterface;
use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\PaginatedTrickOverviewsResult;
use App\Trick\Core\UseCases\Queries\GetPaginatedTrickOverviews\TrickOverview;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PDO;

class GetPaginatedTrickOverviewsQuery implements GetPaginatedTrickOverviewsQueryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /** @throws Exception */
    public function run(int $limit, int $offset): PaginatedTrickOverviewsResult
    {
        $sql = <<<SQL
SELECT t.name AS name,  t.slug AS slug, c1.name  AS categoryName, i.path AS thumbnailUrl, COUNT(c.uuid) OVER (PARTITION BY t.uuid) AS commentsCount
FROM tricks AS t
INNER JOIN trick_categories c1 ON t.category_uuid = c1.uuid
INNER JOIN trick_images i ON t.uuid = i.trick_uuid
LEFT JOIN trick_comments c ON t.uuid = c.trick_uuid
ORDER BY t.created_at DESC, t.name
LIMIT :limit
OFFSET :offset
SQL;

        $statement = $this->entityManager->getConnection()->prepare($sql);

        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->bindValue('offset', (($offset - 1) * $limit), PDO::PARAM_INT);

        /** @var array{name: string, slug: string, categoryName: string, thumbnailUrl: string, commentsCount: int}[] $result */
        $result = $statement->executeQuery()->fetchAllAssociative();

        $overviews = array_map(
            fn (array $trick) => new TrickOverview(
                name: $trick['name'],
                slug: $trick['slug'],
                categoryName: $trick['categoryName'],
                thumbnailUrl: $trick['thumbnailUrl'],
                commentsCount: (int) $trick['commentsCount'],
            ),
            $result
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

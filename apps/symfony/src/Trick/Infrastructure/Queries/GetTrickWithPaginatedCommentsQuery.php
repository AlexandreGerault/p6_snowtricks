<?php

namespace App\Trick\Infrastructure\Queries;

use App\Trick\Core\Image;
use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\GetTrickWithPaginatedCommentsQuery as GetTrickWithPaginatedCommentsQueryInterface;
use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\TrickComment;
use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\TrickWithPaginatedCommentsResult;
use App\Trick\Core\Video;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PDO;

class GetTrickWithPaginatedCommentsQuery implements GetTrickWithPaginatedCommentsQueryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /** @throws Exception */
    public function run(string $slug, int $limit, int $offset): TrickWithPaginatedCommentsResult
    {
        $trickSql = <<<SQL
SELECT t.uuid, t.name, t.slug, t.description, COUNT(tc.uuid) AS commentsCount, c.name as categoryName
FROM tricks t 
INNER JOIN snowtricks.trick_categories c on t.category_uuid = c.uuid
LEFT JOIN trick_comments tc on t.uuid = tc.trick_uuid
WHERE t.slug = :slug
GROUP BY t.uuid
SQL;
        /** @var array{uuid: string, name: string, slug: string, description: string, categoryName: string, commentsCount: int} $trickResult */
        $trickResult = $this->entityManager
            ->getConnection()
            ->executeQuery($trickSql, ['slug' => $slug])
            ->fetchAllAssociative()[0];

        $imagesSql = <<<SQL
SELECT i.path, i.alt FROM trick_images i WHERE i.trick_uuid = :trickUuid;
SQL;
        /** @var array{path: string, alt: string}[] $imagesResult */
        $imagesResult = $this->entityManager
            ->getConnection()
            ->executeQuery($imagesSql, ['trickUuid' => $trickResult['uuid']])
            ->fetchAllAssociative();

        $videosSql = <<<SQL
SELECT v.url FROM snowtricks.trick_videos v WHERE v.trick_uuid = :trickUuid;
SQL;
        /** @var array{url: string}[] $videosResult */
        $videosResult = $this->entityManager
            ->getConnection()
            ->executeQuery($videosSql, ['trickUuid' => $trickResult['uuid']])
            ->fetchAllAssociative();

        $comments = <<<SQL
SELECT c.content, c.created_at, u.username
FROM trick_comments c 
INNER JOIN users u ON c.author_uuid = u.uuid
WHERE c.trick_uuid = :trickUuid
ORDER BY c.created_at DESC
LIMIT :limit
OFFSET :offset;
SQL;
        $statement = $this->entityManager->getConnection()->prepare($comments);

        $statement->bindValue('trickUuid', $trickResult['uuid']);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->bindValue('offset', ($offset - 1) * $limit, PDO::PARAM_INT);

        /** @var array{content: string, created_at: string, username: string}[] $commentsResult */
        $commentsResult = $statement->executeQuery()->fetchAllAssociative();

        return new TrickWithPaginatedCommentsResult(
            total: $trickResult['commentsCount'] / $limit,
            perPage: $limit,
            page: $offset,
            trickName: $trickResult['name'],
            trickSlug: $trickResult['slug'],
            trickCategoryName: $trickResult['categoryName'],
            trickDescription: $trickResult['description'],
            thumbnailUrl: $imagesResult[0]['path'],
            comments: array_map(
                fn (array $comment) => new TrickComment(
                    author: $comment['username'],
                    content: $comment['content'],
                    createdAt: (new \DateTimeImmutable($comment['created_at']))->format('d/m/Y à H:i'),
                ),
                $commentsResult,
            ),
            images: array_map(fn (array $image) => new Image($image['path'], $image['alt']), $imagesResult),
            videos: array_map(fn (array $video) => new Video($video['url']), $videosResult),
        );
    }
}

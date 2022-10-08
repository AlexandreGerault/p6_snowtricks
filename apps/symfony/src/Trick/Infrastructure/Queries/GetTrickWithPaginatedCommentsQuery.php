<?php

namespace App\Trick\Infrastructure\Queries;

use App\Trick\Core\Image;
use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\GetTrickWithPaginatedCommentsQuery as GetTrickWithPaginatedCommentsQueryInterface;
use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\TrickComment;
use App\Trick\Core\UseCases\Queries\GetTrickWithPaginatedComments\TrickWithPaginatedCommentsResult;
use App\Trick\Core\Video;
use App\Trick\Infrastructure\Entity\Comment;
use App\Trick\Infrastructure\Entity\Image as ImageEntity;
use App\Trick\Infrastructure\Entity\Trick;
use App\Trick\Infrastructure\Entity\Video as VideoEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Component\Uid\AbstractUid;

class GetTrickWithPaginatedCommentsQuery implements GetTrickWithPaginatedCommentsQueryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function run(string $slug, int $limit, int $offset): TrickWithPaginatedCommentsResult
    {
        $trick = $this->getTrick($slug);

        $images = $this->getImages($trick['uuid']);

        $videos = $this->getVideos($trick['uuid']);

        $comments = $this->getComments($trick['uuid'], $limit, $offset);

        return new TrickWithPaginatedCommentsResult(
            total: $trick['commentsCount'] / $limit,
            perPage: $limit,
            page: $offset,
            trickName: $trick['name'],
            trickSlug: $trick['slug'],
            trickCategoryName: $trick['categoryName'],
            trickDescription: $trick['description'],
            thumbnailUrl: $trick['thumbnailUrl'],
            comments: array_map(
                fn (array $comment) => new TrickComment(
                    author: $comment['username'],
                    content: $comment['content'],
                    createdAt: $comment['createdAt']->format('d/m/Y à H:i'),
                ),
                $comments,
            ),
            images: array_map(fn (array $image) => new Image($image['path'], $image['alt']), $images),
            videos: array_map(fn (array $video) => new Video($video['url']), $videos),
        );
    }

    /**
     * @return array{
     *     uuid: AbstractUid,
     *     name: string,
     *     slug: string,
     *     categoryName: string,
     *     description: string,
     *     thumbnailUrl: string,
     *     thumbnailAlt: string,
     *     commentsCount: int
     * }
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getTrick(string $slug): array
    {
        return $this->entityManager->createQueryBuilder() // @phpstan-ignore-line
            ->from(Trick::class, 't')
            ->select('t.uuid AS uuid, t.name, t.slug, t.description, COUNT(tc.uuid) AS commentsCount, c.name as categoryName, th.path AS thumbnailUrl, th.alt AS thumbnailAlt')
            ->leftJoin('t.comments', 'tc')
            ->innerJoin('t.category', 'c')
            ->innerJoin('t.thumbnail', 'th')
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->groupBy('t.uuid')
            ->getQuery()
            ->getSingleResult();
    }

    /** @return array{path: string, alt: string}[] */
    public function getImages(AbstractUid $uuid): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(ImageEntity::class, 'i')
            ->select('i.path, i.alt')
            ->innerJoin('i.trick', 't')
            ->where('t.uuid = :trick')
            ->setParameter('trick', $uuid->toBinary())
            ->getQuery()
            ->getResult();
    }

    /** @return array{url: string}[] */
    public function getVideos(AbstractUid $uuid): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(VideoEntity::class, 'v')
            ->select('v.url')
            ->innerJoin('v.trick', 't')
            ->where('t.uuid = :trick')
            ->setParameter('trick', $uuid->toBinary())
            ->getQuery()
            ->getResult();
    }

    /** @return array{content: string, createdAt: \DateTimeImmutable, username: string}[] */
    public function getComments(AbstractUid $uuid, int $limit, int $offset): mixed
    {
        return $this->entityManager->createQueryBuilder()
            ->from(Comment::class, 'c')
            ->select('c.content, c.createdAt, u.username')
            ->innerJoin('c.author', 'u')
            ->where('c.trick = :trick')
            ->setParameter('trick', $uuid)
            ->setMaxResults($limit)
            ->setFirstResult(($offset - 1) * $limit)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}

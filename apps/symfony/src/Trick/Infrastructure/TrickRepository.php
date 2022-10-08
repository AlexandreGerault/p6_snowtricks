<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure;

use App\Security\Infrastructure\Entity\User;
use App\Trick\Core\Image;
use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;
use App\Trick\Core\Video;
use App\Trick\Infrastructure\Entity\Comment;
use App\Trick\Infrastructure\Entity\Image as ImageEntity;
use App\Trick\Infrastructure\Entity\Thumbnail;
use App\Trick\Infrastructure\Entity\Trick as Entity;
use App\Trick\Infrastructure\Entity\Video as VideoEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\AbstractUid;

/**
 * @extends  ServiceEntityRepository<Trick>
 */
class TrickRepository extends ServiceEntityRepository implements TrickGateway
{
    public function __construct(ManagerRegistry $registry, private CategoryRepository $categoryRepository)
    {
        parent::__construct($registry, Entity::class);
    }

    /** @throws \Exception */
    public function save(Trick $trick): void
    {
        $snapshot = $trick->snapshot();
        $category = $this->categoryRepository->findOneBy(['uuid' => $snapshot->categoryId]);

        if (!$category) {
            throw new \Exception('Category not found');
        }

        /** @var ?Entity $entity */
        $entity = $this->findOneBy(['uuid' => $snapshot->uuid]);

        $isNew = false;

        if (null === $entity) {
            $entity = new Entity();
            $isNew = true;
        } else {
            foreach ($entity->images() as $image) {
                $this->_em->remove($image);
            }

            foreach ($entity->videos() as $video) {
                $this->_em->remove($video);
            }

            $this->_em->flush();
        }

        $entity->setUuid($snapshot->uuid);
        $entity->setName($snapshot->name);
        $entity->setDescription($snapshot->description);
        $entity->setCategory($category);
        $entity->setSlug($snapshot->slug);
        $entity->updateThumbnail($snapshot->thumbnail);

        foreach ($snapshot->comments as $comment) {
            $commentSnapshot = $comment->snapshot();

            if (!$entity->comments()->exists(fn (int $key, Comment $c) => $c->uuid()->equals($commentSnapshot->uuid))) {
                /** @var User $user */
                $user = $this->_em->find(User::class, $commentSnapshot->userId);

                $newComment = new Comment();
                $newComment->setUuid($commentSnapshot->uuid);
                $newComment->setTrick($entity);
                $newComment->setAuthor($user);
                $newComment->setContent($commentSnapshot->content);
                $newComment->setCreatedAt($commentSnapshot->createdAt);

                $entity->addComment($newComment);
            }
        }

        foreach ($snapshot->images as $image) {
            $entity->addImage($image);
        }

        foreach ($snapshot->videos as $video) {
            $entity->addVideo($video);
        }

        if ($isNew) {
            $this->_em->persist($entity);
        }

        $this->_em->flush();
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    /** @throws \Exception */
    public function get(AbstractUid $trickId): Trick
    {
        /** @var ?Entity $entity */
        $entity = $this->findOneBy(['uuid' => $trickId->toRfc4122()]);

        if (!$entity) {
            throw new \Exception('Trick not found');
        }

        return new Trick(
            $entity->uuid(),
            $entity->name(),
            $entity->description(),
            $entity->category()->uuid(),
            $entity->slug(),
            new Image($entity->thumbnail()->path(), $entity->thumbnail()->alt()),
            array_map(fn (ImageEntity $image) => new Image($image->path(), $image->alt()), $entity->images()->toArray()),
            array_map(fn (VideoEntity $video) => new Video($video->url()), $entity->videos()->toArray()),
        );
    }
}

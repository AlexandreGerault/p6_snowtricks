<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure;

use App\Trick\Core\Trick;
use App\Trick\Core\TrickGateway;
use App\Trick\Infrastructure\Entity\Category;
use App\Trick\Infrastructure\Entity\Trick as Entity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV6;

/**
 * @extends  ServiceEntityRepository<Trick>
 */
class TrickRepository extends ServiceEntityRepository implements TrickGateway
{
    public function __construct(ManagerRegistry $registry, private CategoryRepository $categoryRepository)
    {
        parent::__construct($registry, Entity::class);
    }

    public function save(Trick $trick): void
    {
        $snapshot = $trick->snapshot();
        $category = $this->categoryRepository->findOneBy(['uuid' => $snapshot->categoryId]);

        if (!$category) {
            throw new \Exception('Category not found');
        }

        $entity = new Entity;
        $entity->setUuid($snapshot->uuid);
        $entity->setName($snapshot->name);
        $entity->setDescription($snapshot->description);
        $entity->setCategory($category);
        $entity->setSlug($snapshot->slug);

        foreach ($snapshot->images as $image) {
            $entity->addImage($image);
        }

        foreach ($snapshot->videos as $video) {
            $entity->addVideo($video);
        }

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    /** @throws \Exception */
    public function get(AbstractUid $trickId): Trick
    {
        throw new \Exception('Not implemented yet');
    }
}

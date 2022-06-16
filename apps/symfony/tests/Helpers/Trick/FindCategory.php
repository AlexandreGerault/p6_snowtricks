<?php

namespace App\Tests\Helpers\Trick;

use App\Trick\Infrastructure\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

trait FindCategory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getCategoryUuid(ContainerInterface $container): string
    {
        $em = $container->get(EntityManagerInterface::class);

        try {
            /** @var Category $category */
            $category = $em->createQueryBuilder()->select("category")
                ->from(Category::class, "category")
                ->where("category.name = :name")
                ->setParameter("name", "Rider")
                ->getQuery()
                ->getOneOrNullResult();

            return $category->uuid()->toRfc4122();
        } catch (NonUniqueResultException $e) {
            $this->fail("Category not found");
        }
    }
}
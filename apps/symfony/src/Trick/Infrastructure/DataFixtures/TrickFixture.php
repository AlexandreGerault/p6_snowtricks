<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\DataFixtures;

use App\Trick\Infrastructure\Entity\Category;
use App\Trick\Infrastructure\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;

class TrickFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /** @var Category $category */
        $category = $this->getReference(CategoryFixture::CATEGORY_NAME_RIDER);

        $trick = new Trick();
        $trick->setUuid(Uuid::v6());
        $trick->setName('Trick 1');
        $trick->setSlug('trick-1');
        $trick->setDescription('Description 1');
        $trick->setCategory($category);

        $manager->persist($trick);

        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\DataFixtures\Test;

use App\Trick\Infrastructure\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class CategoryFixture extends Fixture implements FixtureGroupInterface
{
    public const CATEGORY_UUID_RIDER = '71494473-142a-4e97-9872-7fb787a07573';
    public const CATEGORY_NAME_RIDER = 'Rider';

    public const CATEGORY_UUID_GRAB = '8d00de52-6777-42b7-8560-1c6807a1e8ae';
    public const CATEGORY_NAME_GRAB = 'Grab';

    public const CATEGORY_UUID_ROTATION = 'bf881534-07a7-408e-b1e7-57aeab02d6ec';
    public const CATEGORY_NAME_ROTATION = 'Rotation';

    public const CATEGORY_UUID_FLIP = 'a3ade03f-7f96-43fb-9e30-ae3e321a9abb';
    public const CATEGORY_NAME_FLIP = 'Flip';

    public const CATEGORY_UUID_SLIDES = '71b763b8-d2ae-4e03-acbd-3e966eb4c87c';
    public const CATEGORY_NAME_SLIDES = 'Slides';

    public function load(ObjectManager $manager): void
    {
        $rider = (new Category())
            ->setUuid(UuidV4::fromString(self::CATEGORY_UUID_RIDER))
            ->setName(self::CATEGORY_NAME_RIDER);
        $manager->persist($rider);

        $grab = (new Category())
            ->setUuid(UuidV4::fromString(self::CATEGORY_UUID_GRAB))
            ->setName(self::CATEGORY_NAME_GRAB);
        $manager->persist($grab);

        $rotation = (new Category())
            ->setUuid(UuidV4::fromString(self::CATEGORY_UUID_FLIP))
            ->setName(self::CATEGORY_NAME_ROTATION);
        $manager->persist($rotation);

        $flip = (new Category())
            ->setUuid(UuidV4::fromString(self::CATEGORY_UUID_ROTATION))
            ->setName(self::CATEGORY_NAME_FLIP);
        $manager->persist($flip);

        $slide = (new Category())
            ->setUuid(UuidV4::fromString(self::CATEGORY_UUID_SLIDES))
            ->setName(self::CATEGORY_NAME_SLIDES);
        $manager->persist($slide);

        $manager->flush();

        $this->addReference(self::CATEGORY_NAME_RIDER, $rider);
    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}

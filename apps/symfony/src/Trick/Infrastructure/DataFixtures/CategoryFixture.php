<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\DataFixtures;

use App\Trick\Infrastructure\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class CategoryFixture extends Fixture
{
    public const CATEGORY_UUID_RIDER = 'd35effa2-f180-4d3b-b656-14b586264ffc';
    public const CATEGORY_NAME_RIDER = 'Rider';

    public const CATEGORY_UUID_GRAB = 'a2daae4c-f7e9-4e0f-9193-1736e32766d5';
    public const CATEGORY_NAME_GRAB = 'Grab';

    public const CATEGORY_UUID_ROTATION = '90623086-d636-43d4-aadb-130539c3606c';
    public const CATEGORY_NAME_ROTATION = 'Rotation';

    public const CATEGORY_UUID_FLIP = 'b56dde60-d114-4193-9816-8dbd5c370b32';
    public const CATEGORY_NAME_FLIP = 'Flip';

    public const CATEGORY_UUID_SLIDES = 'd684cea9-47f9-4de6-bb5e-e2a4cc720fee';
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
    }
}

<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\DataFixtures\Test;

use App\Trick\Core\Image;
use App\Trick\Core\ImageStorage;
use App\Trick\Core\Video;
use App\Trick\Infrastructure\Entity\Category;
use App\Trick\Infrastructure\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class TrickFixture extends Fixture implements FixtureGroupInterface
{
    public function __construct(private readonly ImageStorage $imageStorage)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Category $category */
        $category = $this->getReference(CategoryFixture::CATEGORY_NAME_RIDER);

        for ($i = 0; $i < 60; ++$i) {
            $trick = new Trick();
            $trick->setUuid(Uuid::v4());
            $trick->setName('Trick '.$i);
            $trick->setSlug('trick-'.$i);
            $trick->setDescription('Description '.$i);
            $trick->setCategory($category);

            $thumbnail = $this->copyAssetImageForTrick($trick, '180-thumbnail.jpg');
            $trick->setThumbnail($thumbnail);

            $image = $this->copyAssetImageForTrick($trick, '180-1.jpg');
            $trick->addImage($image);

            $trick->addVideo(new Video('https://www.youtube.com/embed/pbMwTqkKSps'));

            $manager->persist($trick);
        }

        $manager->flush();
    }

    private function copyAssetImageForTrick(Trick $trick, string $imageName): Image
    {
        $path = $this->imageStorage->save(__DIR__.'/../../../../../assets/fixtures/tricks/'.$imageName);

        return new Image('/storage/uploads/tricks/'.basename($path), $imageName);
    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}

<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\DataFixtures;

use App\Trick\Core\Image;
use App\Trick\Core\ImageStorage;
use App\Trick\Core\Video;
use App\Trick\Infrastructure\Entity\Category;
use App\Trick\Infrastructure\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class TrickFixture extends Fixture
{
    public function __construct(private readonly ImageStorage $imageStorage)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Category $category */
        $category = $this->getReference(CategoryFixture::CATEGORY_NAME_RIDER);

        $trick = new Trick();
        $trick->setUuid(Uuid::v4());
        $trick->setName('Trick 1');
        $trick->setSlug('trick-1');
        $trick->setDescription('Description 1');
        $trick->setCategory($category);

        $image = $this->copyAssetImageForTrick($trick, '180-1.jpg');
        $trick->addImage($image);

        $trick->addVideo(new Video('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));

        $manager->persist($trick);

        $manager->flush();
    }

    private function copyAssetImageForTrick(Trick $trick, string $imageName): Image
    {
        $path = $this->imageStorage->save(__DIR__.'/../../../../assets/fixtures/tricks/'.$imageName);

        return new Image($path, $imageName);
    }
}

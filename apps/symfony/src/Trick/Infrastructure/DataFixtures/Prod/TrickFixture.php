<?php

declare(strict_types=1);

namespace App\Trick\Infrastructure\DataFixtures\Prod;

use App\Trick\Core\Image;
use App\Trick\Core\ImageStorage;
use App\Trick\Core\Video;
use App\Trick\Infrastructure\Entity\Category;
use App\Trick\Infrastructure\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;

class TrickFixture extends Fixture implements FixtureGroupInterface
{
    private ObjectManager $manager;

    public function __construct(private readonly ImageStorage $imageStorage, private readonly SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->registerTrick(
            "180",
            CategoryFixture::CATEGORY_NAME_ROTATION,
            "Saut demi-tour",
            "JMS2PGAFMcE"
        );

        $this->registerTrick(
            "Mute",
            CategoryFixture::CATEGORY_NAME_GRAB,
            "Saisir sa planche en plein vol",
            "51sQRIK-TEI"
        );

        $this->registerTrick(
            "Mc Twist",
            CategoryFixture::CATEGORY_NAME_FLIP,
            "Le Mc Twist est un flip (rotation verticale) agrémenté d'une vrille. Un saut plutôt périlleux réservé aux riders les plus confirmés. Le champion Shaun White s'est illustré par un Double Mc Twist 1260 lors de sa session de Half-Pipe aux Jeux Olympiques de Vancouver en 2010.",
            "YQIvm_2ay-U"
        );

        $this->registerTrick(
            "Lipslide",
            CategoryFixture::CATEGORY_NAME_SLIDES,
            "Le lispslide consiste à glisser sur un obstacle en mettant la planche perpendiculaire à celui-ci. Un jib à 90 degrés en d'autres termes. Le lipslide peut se faire en avant ou en arrière. Frontside ou backside, donc.",
            "LSVn5aI56aU"
        );

        $this->registerTrick(
            "Triple cork",
            CategoryFixture::CATEGORY_NAME_FLIP,
            "Saut en hauteurs avec trois rotations verticales",
            "Br6ZJM01I6s"
        );

        $this->registerTrick(
            "Wildcat",
            CategoryFixture::CATEGORY_NAME_FLIP,
            "Wildcats are great because they are so achievable on small features, which also means you can practice them just about anywhere. Pack down a little booter with a kicky lip and you're good to go!",
            "7KUpodSrZqI"
        );

        $this->registerTrick(
            "Alley-oop",
            CategoryFixture::CATEGORY_NAME_FLIP,
            "Saut en l'air",
            "DYX-1qzj-YE"
        );

        $this->registerTrick(
            "Back-flip",
            CategoryFixture::CATEGORY_NAME_FLIP,
            "Saut en arrière",
            "SlhGVnFPTDE"
        );

        $this->registerTrick(
            "Front-flip",
            CategoryFixture::CATEGORY_NAME_ROTATION,
            "Saut demi-tour en avant",
            "eGJ8keB1-JM"
        );

        $this->registerTrick(
            "Indy",
            CategoryFixture::CATEGORY_NAME_GRAB,
            "Attraper sa planche en plein saut",
            "6yA3XqjTh_w"
        );

        $manager->flush();
    }

    private function copyAssetImageForTrick(Trick $trick, string $imageName): Image
    {
        $path = $this->imageStorage->save(__DIR__.'/../../../../../assets/fixtures/tricks/'.$imageName);

        return new Image('/storage/uploads/tricks/'.basename($path), $imageName);
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }

    private function registerTrick(string $name, string $categoryName, string $description, string $contentId)
    {
        /** @var Category $category */
        $category = $this->getReference($categoryName);
        $slug = $this->slugger->slug($name)->lower()->toString();

        $trick = new Trick();
        $trick->setUuid(Uuid::v4());
        $trick->setName($name);
        $trick->setSlug($slug);
        $trick->setDescription($description);
        $trick->setCategory($category);

        $thumbnail = $this->copyAssetImageForTrick($trick, $slug . '-thumbnail.jpg');
        $trick->setThumbnail($thumbnail);

        $image = $this->copyAssetImageForTrick($trick, $slug.'-1.jpg');
        $trick->addImage($image);

        $image = $this->copyAssetImageForTrick($trick, $slug.'-2.jpg');
        $trick->addImage($image);

        $trick->addVideo(new Video('https://www.youtube.com/embed/' . $contentId));

        $this->manager->persist($trick);
    }
}

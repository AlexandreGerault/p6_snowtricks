<?php

declare(strict_types=1);

namespace App\Tests\Web\Trick;

use App\Security\Infrastructure\DataFixtures\UserFixture;
use App\Tests\Helpers\File\File;
use App\Tests\Helpers\Security\FetchUser;
use App\Tests\Helpers\Trick\FindCategory;
use App\Tests\Helpers\Trick\FindTrick;
use App\Tests\Web\WebTestCase;
use App\Trick\Core\ImageStorage;
use App\Trick\Infrastructure\Entity\Image;
use App\Trick\Infrastructure\Entity\Video;
use App\Trick\Infrastructure\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;

class EditTrickTest extends WebTestCase
{
    use FindCategory;
    use FindTrick;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testEditTrick()
    {
        $client = static::createClient();

        $client->loginUser(FetchUser::new($client->getContainer())->fetchUserByMail(UserFixture::ADMIN_MAIL));

        $crawler = $client->request(Request::METHOD_GET, "/figure/modifier/{$this->getTrickSlug()}");

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name="edit_trick"]')->form();
        $csrfTokenField = $form->get('edit_trick[_token]');

        $params = [
            'edit_trick' => [
                '_token' => $csrfTokenField->getValue(),
                'name' => 'Figure 2',
                'description' => 'Description',
                'category' => $this->getCategoryUuid($client->getContainer()),
                'images' => [['alt' => 'Figure de snow']],
                'videos' => [
                    ['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ],
            ],
        ];

        $files = [
            'edit_trick' => [
                'images' => [['image' => File::image('figure.jpg')]],
            ],
        ];

        $client->request(Request::METHOD_POST, "/figure/modifier/{$this->getTrickSlug()}", $params, $files);
        $this->assertResponseRedirects('/');

        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('div.flash-success')->count());
        $crawler->filter('div.flash-success')->each(function ($node) {
            $this->assertStringContainsString('La figure a bien été modifiée', $node->text());
        });

        $this->assertCount(1, $client->getContainer()->get(ImageStorage::class)->findAll());
        $this->assertCount(1, $client->getContainer()->get(TrickRepository::class)->findBy(['name' => 'Figure 2']));
    }

    public function testEditTrickWithoutChangingImageAndAddingOneVideo(): void
    {
        $client = static::createClient();

        $client->loginUser(FetchUser::new($client->getContainer())->fetchUserByMail(UserFixture::ADMIN_MAIL));

        $crawler = $client->request(Request::METHOD_GET, "/figure/modifier/{$this->getTrickSlug()}");

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name="edit_trick"]')->form();
        $csrfTokenField = $form->get('edit_trick[_token]');

        $params = [
            'edit_trick' => [
                '_token' => $csrfTokenField->getValue(),
                'name' => 'Figure 2',
                'description' => 'Description',
                'category' => $this->getCategoryUuid($client->getContainer()),
                'images' => [
                    [
                        'alt' => 'Figure de snow',
                        'path' => '/usr/src/app/assets/fixtures/tricks/0a996066-fc6c-4a0f-be4b-c51e7f673c3d.jpg',
                    ],
                ],
                'videos' => [
                    ['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                    ['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXaQ'],
                ],
            ],
        ];

        $client->request(Request::METHOD_POST, "/figure/modifier/{$this->getTrickSlug()}", $params);
        $this->assertResponseRedirects('/');

        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('div.flash-success')->count());
        $crawler->filter('div.flash-success')->each(function ($node) {
            $this->assertStringContainsString('La figure a bien été modifiée', $node->text());
        });

        $this->assertCount(1, $client->getContainer()->get(ImageStorage::class)->findAll());
        $this->assertCount(1, $client->getContainer()->get(TrickRepository::class)->findBy(['name' => 'Figure 2']));

        /** @var EntityManagerInterface $em */
        $em = $client->getContainer()->get(EntityManagerInterface::class);

        $images = $em->createQueryBuilder()->from(Image::class, 'i')->select('i')->getQuery()->execute();
        $videos = $em->createQueryBuilder()->from(Video::class, 'v')->select('v')->getQuery()->execute();

        $this->assertCount(60, $images);
        $this->assertCount(61, $videos);
    }
}

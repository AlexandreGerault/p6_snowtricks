<?php

declare(strict_types=1);

namespace App\Tests\Web\Trick;

use App\Security\Infrastructure\DataFixtures\Test\UserFixture;
use App\Tests\Helpers\File\File;
use App\Tests\Helpers\Security\FetchUser;
use App\Tests\Helpers\Trick\FindCategory;
use App\Tests\Web\WebTestCase;
use App\Trick\Core\ImageStorage;
use App\Trick\Infrastructure\TrickRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegisterTrickTest extends WebTestCase
{
    use FindCategory;

    public function testGuestCannotRegisterTrick(): void
    {
        $client = static::createClient();

        $loginUrl = $client
            ->getContainer()
            ->get(UrlGeneratorInterface::class)
            ->generate('app_login', referenceType: UrlGeneratorInterface::ABSOLUTE_URL);

        $client->request(Request::METHOD_POST, '/figure/ajouter');
        $this->assertResponseRedirects($loginUrl);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testRegisterTrick(): void
    {
        $client = static::createClient();

        $client->loginUser(FetchUser::new($client->getContainer())->fetchUserByMail(UserFixture::ADMIN_MAIL));

        $crawler = $client->request(Request::METHOD_GET, '/figure/ajouter');
        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name="register_trick"]')->form();
        $csrfTokenField = $form->get('register_trick[_token]');

        $params = [
            'register_trick' => [
                '_token' => $csrfTokenField->getValue(),
                'name' => 'Figure',
                'description' => 'Description',
                'category' => $this->getCategoryUuid($client->getContainer()),
                'thumbnail' => ['alt' => 'Thumbnail de snow'],
                'images' => [['alt' => 'Figure de snow']],
                'videos' => [
                    ['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ],
            ],
        ];
        $files = [
            'register_trick' => [
                'thumbnail' => ['image' => File::image('figure.jpg')],
                'images' => [['image' => File::image('figure.jpg')]],
            ],
        ];

        $client->request(Request::METHOD_POST, '/figure/ajouter', $params, $files);
        $this->assertResponseRedirects('/');

        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('div.flash-success')->count());
        $crawler->filter('div.flash-success')->each(function ($node) {
            $this->assertStringContainsString('La figure a bien été créée', $node->text());
        });

        $this->assertCount(2, $client->getContainer()->get(ImageStorage::class)->findAll());
        $this->assertCount(1, $client->getContainer()->get(TrickRepository::class)->findBy(['name' => 'Figure']));
    }
}

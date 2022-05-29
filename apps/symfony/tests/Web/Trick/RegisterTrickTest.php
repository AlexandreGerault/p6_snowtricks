<?php

declare(strict_types=1);

namespace App\Tests\Web\Trick\RegisterTrick;

use App\Security\DataFixtures\UserFixture;
use App\Test\Web\WebTestCase;
use App\Tests\Helpers\File\File;
use App\Tests\Helpers\Security\FetchUser;
use App\Trick\Core\ImageStorage;
use App\Trick\Infrastructure\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegisterTrickTest extends WebTestCase
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

    public function testGuestCannotRegisterTrick(): void
    {
        $client = static::createClient();

        $loginUrl = $client
            ->getContainer()
            ->get(UrlGeneratorInterface::class)
            ->generate("app_login", referenceType: UrlGeneratorInterface::ABSOLUTE_URL);

        $client->request(Request::METHOD_POST, "/figure/ajouter");
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
        $csrfTokenField = $form->get("register_trick[_token]");

        $params = [
            'register_trick' => [
                '_token' => $csrfTokenField->getValue(),
                'name' => 'Figure',
                'description' => 'Description',
                'category' => $this->getCategoryUuid($client->getContainer()),
                'images' => [['alt' => 'Figure de snow']],
                'videos' => [
                    'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                ],
            ]
        ];
        $files = [
            'register_trick' => [
                'images' => [['image' => File::image("figure.jpg")]]
            ],
        ];

        $client->request(Request::METHOD_POST, '/figure/ajouter', $params, $files);
        $this->assertResponseRedirects('/');

        $crawler = $client->followRedirect();
        $crawler->filter('div.flash-success')->each(function ($node) {
            $this->assertStringContainsString('La figure a bien été créée', $node->text());
        });

        $this->assertCount(1, $client->getContainer()->get(ImageStorage::class)->findAll());
    }
}

<?php

namespace App\Tests\Web\Trick;

use App\Security\Infrastructure\DataFixtures\UserFixture;
use App\Tests\Helpers\Security\FetchUser;
use App\Tests\Helpers\Trick\FindTrick;
use App\Tests\Web\WebTestCase;
use App\Trick\Infrastructure\Entity\Trick;
use App\Trick\Infrastructure\TrickRepository;
use Symfony\Component\HttpFoundation\Request;

class CommentTrickTest extends WebTestCase
{
    use FindTrick;

    public function testItCanCommentATrickWhenUserIsAuthenticated(): void
    {
        $client = static::createClient();

        $client->loginUser(FetchUser::new($client->getContainer())->fetchUserByMail(UserFixture::ADMIN_MAIL));

        $crawler = $client->request(Request::METHOD_GET, "/figure/{$this->getTrickSlug()}");

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name="comment_trick"]')->form();
        $csrfTokenField = $form->get('comment_trick[_token]');

        $params = [
            'comment_trick' => [
                '_token' => $csrfTokenField->getValue(),
                'message' => 'Commentaire',
            ],
        ];

        $client->request(Request::METHOD_POST, "/figure/{$this->getTrickSlug()}", $params);
        $this->assertResponseRedirects("/figure/{$this->getTrickSlug()}");

        $crawler = $client->followRedirect();
        $crawler->filter('div.flash-success')->each(function ($node) {
            $this->assertStringContainsString('Votre commentaire a bien été pris en compte', $node->text());
        });

        /** @var TrickRepository $trickRepository */
        $trickRepository = $client->getContainer()->get(TrickRepository::class);

        $trick = $trickRepository->findOneBy(['name' => 'Trick 1']);
        $this->assertInstanceOf(Trick::class, $trick);
        $this->assertCount(1, $trick->comments());
    }

    public function testAnUnauthenticatedUserCannotCommentATrick(): void
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET, "/figure/{$this->getTrickSlug()}");

        $this->assertResponseIsSuccessful();

        $this->assertEquals(0, $crawler->filter('form[name="comment_trick"]')->count());
    }
}

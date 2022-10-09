<?php

declare(strict_types=1);

namespace App\Tests\Web\Trick;

use App\Tests\Helpers\Trick\FindCategory;
use App\Tests\Helpers\Trick\FindTrick;
use App\Tests\Web\WebTestCase;

class ShowTrickTest extends WebTestCase
{
    use FindCategory;
    use FindTrick;

    public function testListTricks()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/figure/'.$this->getTrickSlug('Trick 1'));

        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $crawler->filter('article'));

        $this->assertSelectorTextContains('h1', 'Trick 1');
        $this->assertSelectorTextContains('p', 'Description 1');
    }
}

<?php

namespace App\Tests\Web\Trick;

use App\Tests\Web\WebTestCase;

class ListTricksTest extends WebTestCase
{
    public function testListTricks()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertCount(9, $crawler->filter('article.trick'));
    }
}

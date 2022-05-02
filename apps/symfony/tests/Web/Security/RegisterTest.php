<?php

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;

it('shows the form to an unauthenticated user', function () {
    /** @var KernelBrowser $client */
    $client = static::createClient();

    $client->request(Request::METHOD_GET, '/inscription');

    $this->assertResponseIsSuccessful();
});

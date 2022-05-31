<?php

declare(strict_types=1);

namespace App\Tests\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as TestCase;
use Symfony\Component\Filesystem\Filesystem;

class WebTestCase extends TestCase
{
    protected function tearDown(): void
    {
        $rootDir =  $this->getContainer()->getParameter("kernel.project_dir");
        $uploadedFiles = "{$rootDir}/storage/testing/uploads";

        $this
            ->getContainer()
            ->get(Filesystem::class)
            ->remove($uploadedFiles);

        parent::tearDown();
    }
}

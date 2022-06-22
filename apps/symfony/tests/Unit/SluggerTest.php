<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Trick\Core\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setLocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function testItCanSlugifyASpaceSeparatedString(): void
    {
        $this->assertEquals('hello-world', Slugger::slugify('Hello World'));
    }

    public function testItCanSlugifyAStringWithSpecialCharacters(): void
    {
        $this->assertEquals('bonjour-a-tous', Slugger::slugify('Bonjour à tous'));
    }
}

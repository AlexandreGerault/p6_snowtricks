<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Shared\Constraints\UniqueFieldValidator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueFieldValidatorTest extends TestCase
{
    public function test_it_cannot_handle_validate_field_that_are_not_unique_field(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $validator = new UniqueFieldValidator($this->createMock(EntityManagerInterface::class));

        $validator->validate('Hello World', new Image());
    }
}

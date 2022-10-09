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
    public function testItCannotHandleValidateFieldThatAreNotUniqueField(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $validator = new UniqueFieldValidator($this->createMock(EntityManagerInterface::class));

        $validator->validate('Hello World', new Image());
    }
}

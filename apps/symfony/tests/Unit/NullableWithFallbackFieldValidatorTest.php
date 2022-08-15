<?php

namespace App\Tests\Unit;

use App\Tests\Helpers\File\File;
use App\Trick\UserInterface\Form\Constraints\NullableImageWithDefaultPath;
use App\Trick\UserInterface\Form\Constraints\NullableImageWithDefaultPathValidator;
use App\Trick\UserInterface\Form\Type\ImageDTO;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/** @extends ConstraintValidatorTestCase<NullableImageWithDefaultPathValidator> */
class NullableWithFallbackFieldValidatorTest extends ConstraintValidatorTestCase
{
    /** @var NullableImageWithDefaultPathValidator */
    protected $validator;

    protected function createValidator(): NullableImageWithDefaultPathValidator
    {
        return new NullableImageWithDefaultPathValidator();
    }

    public function testItCanHaveAValueToTheNullableFieldAndNullToTheFallback(): void
    {
        $constraint = new NullableImageWithDefaultPath();

        $imageDTO = new ImageDTO();

        $imageDTO->image = File::image('fake.jpg');
        $imageDTO->path = null;
        $imageDTO->alt = 'fake';

        $this->validator->validate($imageDTO, $constraint);

        $this->assertNoViolation();
    }

    public function testItRaisesAViolationWhenTheImageIsNullAndTheFallbackIsAlsoNull(): void
    {
        $constraint = new NullableImageWithDefaultPath();

        $imageDTO = new ImageDTO();

        $imageDTO->image = null;
        $imageDTO->path = null;
        $imageDTO->alt = 'fake';

        $this->validator->validate($imageDTO, $constraint);

        $this->buildViolation($constraint->message)->assertRaised();
    }

    public function testItCanHaveANullImageWhenHavingAPath(): void
    {
        $constraint = new NullableImageWithDefaultPath();

        $imageDTO = new ImageDTO();

        $imageDTO->image = null;
        $imageDTO->path = File::image('fake.jpg');
        $imageDTO->alt = 'fake';

        $this->validator->validate($imageDTO, $constraint);

        $this->assertNoViolation();
    }
}

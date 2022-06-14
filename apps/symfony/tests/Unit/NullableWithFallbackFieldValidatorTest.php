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

    public function test_it_can_have_a_value_to_the_nullable_field_and_null_to_the_fallback(): void
    {
        $constraint = new NullableImageWithDefaultPath();

        $imageDTO = new ImageDTO();

        $imageDTO->image = File::image("fake.jpg");
        $imageDTO->path = null;
        $imageDTO->alt = "fake";

        $this->validator->validate($imageDTO, $constraint);

        $this->assertNoViolation();
    }

    public function test_it_raises_a_violation_when_the_image_is_null_and_the_fallback_is_also_null(): void
    {
        $constraint = new NullableImageWithDefaultPath();

        $imageDTO = new ImageDTO();

        $imageDTO->image = null;
        $imageDTO->path = null;
        $imageDTO->alt = "fake";

        $this->validator->validate($imageDTO, $constraint);

        $this->buildViolation($constraint->message)->assertRaised();
    }

    public function test_it_can_have_a_null_image_when_having_a_path(): void
    {
        $constraint = new NullableImageWithDefaultPath();

        $imageDTO = new ImageDTO();

        $imageDTO->image = null;
        $imageDTO->path = File::image("fake.jpg");
        $imageDTO->alt = "fake";

        $this->validator->validate($imageDTO, $constraint);

        $this->assertNoViolation();
    }
}

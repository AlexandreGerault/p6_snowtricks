<?php

namespace App\Trick\UserInterface\Form\Constraints;

use App\Trick\UserInterface\Form\Type\ImageDTO;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NullableImageWithDefaultPathValidator extends ConstraintValidator
{
    /**
     * @param ImageDTO                     $value
     * @param NullableImageWithDefaultPath $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value->image && null === $value->path) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}

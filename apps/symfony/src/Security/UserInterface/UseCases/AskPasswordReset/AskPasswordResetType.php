<?php

namespace App\Security\UserInterface\UseCases\AskPasswordReset;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

class AskPasswordResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse mail',
                'attr' => [
                    'placeholder' => 'john-doe@example.com',
                ],
            ])
        ;
    }
}

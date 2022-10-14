<?php

namespace App\Security\UserInterface\UseCases\AskPasswordReset;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AskPasswordResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Votre nom d\'utilisateur',
                'attr' => [
                    'placeholder' => 'John Doe',
                ],
            ])
        ;
    }
}

<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Fichier',
            ])
            ->add('path', TextType::class, [
                'attr' => [
                    'readonly' => true,
                    'hidden' => true,
                ],
                'label' => false
            ])
            ->add('alt', TextType::class, [
                'label' => 'Description',
            ])
        ;

        if ($options['can_delete'] ?? false) {
            $builder->add('delete', ButtonType::class, [
                'label' => 'Supprimer',
                'attr' => [
                    'class' => 'button-red remove-button',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImageDTO::class,
            'can_delete' => false,
        ]);
    }
}

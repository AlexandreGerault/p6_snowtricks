<?php

declare(strict_types=1);

namespace App\Trick\UserInterface\UseCases\Web\EditTrick;

use App\Trick\UserInterface\Form\Type\ImageType;
use App\Trick\UserInterface\Form\Type\TrickType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditTrickType extends TrickType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('thumbnail', ImageType::class, [
                'label' => 'Image principale',
                'required' => false,
            ])
            ->add('add_image', ButtonType::class, [
                'label' => 'Ajouter une image',
                'attr' => [
                    'class' => 'add-button',
                    'data-collection' => $builder->get('images')->getAttribute('id'),
                ],
            ])
            ->add('add_video', ButtonType::class, [
                'label' => 'Ajouter une vidéo',
                'attr' => [
                    'class' => 'add-button',
                    'data-collection' => $builder->get('videos')->getAttribute('id'),
                ],
            ])
            ->add('Modifier', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditTrickDTO::class,
        ]);
    }
}

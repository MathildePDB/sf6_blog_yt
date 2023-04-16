<?php

namespace App\Form;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsAdminType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ranking', ChoiceType::class, [
                'label' => 'Note',
                'attr' => [
                    'class' => 'form-select m-2'
                ],
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'form-control m-2'
                ]
            ])
            ->add('user', EntityType::class, [
                'label' => 'Utilisateur',
                'class' => Users::class,
                'attr' => [
                    'class' => 'form-select m-2'
                ]
            ])
            ->add('post', EntityType::class, [
                'label' => 'Article',
                'class' => Posts::class,
                'attr' => [
                    'class' => 'form-select m-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersRolesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-select m-2',
                ],
                'choices' => [
                    'utilisateur' => 'ROLE_USER',
                    'blog administrateur' => 'ROLE_BLOG_ADMIN',
                    'administrateur' => 'ROLE_ADMIN'
                ],
                'label' => 'Choisir le rôle',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TypeTextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control m-2'
                ]
            ])
            ->add('parent', EntityType::class, [
                'class' => Categories::class,
                'attr' => [
                    'class' => 'form-select m-2'
                ],
                'choice_label' => 'name',
                'label' => 'Catégorie parente',
                'group_by' => 'parent.name',
                'query_builder' => function(CategoriesRepository $categoriesRepository) {
                    return $categoriesRepository->createQueryBuilder('c')
                    ->where('c.parent IS NULL')
                    ->orderBy('c.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}

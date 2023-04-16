<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Posts;
use App\Entity\Users;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class PostsType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control m-2',
                    'placeholder' => 'Titre'
                ]
            ])
            ->add('subtitle', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control m-2',
                    'placeholder' => 'Sous-titre'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control m-2',
                    'placeholder' => 'Contenu'
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categories::class, 
                'attr' => [
                    'class' => 'form-select m-2'
                ],
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'group_by' => 'parent.name',
                'query_builder' => function(CategoriesRepository $categoriesRepository) {
                    return $categoriesRepository->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'attr' => [
                    'class' => 'd-none'
                ],
                'query_builder' => function($repository) {
                    return $repository->createQueryBuilder('u')
                        ->where('u.id = :userId')
                        ->setParameter('userId', $this->security->getUser()->getId());
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}

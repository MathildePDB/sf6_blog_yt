<?php

namespace App\Form;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class CommentsType extends AbstractType
{
    private $security;

    private $requestStack;

    private $em;

    public function __construct(Security $security, RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ranking', ChoiceType::class, [
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
                'attr' => [
                    'class' => 'form-control m-2'
                ]
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
            ->add('post', EntityType::class, [
                'class' => Posts::class,
                'attr' => [
                    'class' => 'd-none'
                ],
                'query_builder' => function($repository) {
                    return $repository->createQueryBuilder('p')
                        ->where('p.id = :postId')
                        ->setParameter('postId', $this->em->getRepository(Posts::class)->findOneBy(['slug' => $this->requestStack->getCurrentRequest()->attributes->get('slug')])->getId());
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
            'em' => null
        ]);
    }
}

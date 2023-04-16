<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class PublicController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getDoctrine(): EntityManagerInterface
    {
        return $this->entityManager;
    }
    
    #[Route(path: '/', name: 'app_home')]
    public function index(PostsRepository $postsRepository): Response
    {
        $posts = $postsRepository->findBy([], ['created_at' => 'DESC'], 6);
        
        if (empty($posts)) {
            return $this->render('public/home.html.twig', [
                'posts' => null
            ]);
        }

        return $this->render('public/home.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route(path: '/articles', name: 'app_articles')]
    public function indexBlog(PaginatorInterface $paginator, Request $request, EntityManagerInterface $em): Response
    {   

        $datas = $this->getDoctrine()->getRepository(Posts::class)->findBy([], ['created_at' => 'desc']);
        // $posts = $postsRepository->findBy([], ['created_at' => 'DESC']);

        $posts = $paginator->paginate($datas, $request->query->getInt('page', 1), 5);
        
        if (empty($posts)) {
            return $this->render('public/blog_index.html.twig', [
                'posts' => null
            ]);
        }

        return $this->render('public/blog_index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/articles/{slug}', name: 'app_articles_show')]
    public function showBlog(Posts $post, Request $request, CommentsRepository $commentsRepository): Response
    {
        $comment = new Comments();
        $user = $this->getUser();

        if ($user) {
            $form = $this->createForm(CommentsType::class);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $comment = $form->getData();
                $commentsRepository->save($comment, true);
    
                return $this->redirectToRoute('app_articles_show', ['slug' => $post->getSlug()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('public/blog_show.html.twig', [
                'post' => $post,
                'form' => $form->createView(),
                'comments' => $commentsRepository->findBy(['post' => $post->getId()])
            ]);
        }

        return $this->render('public/blog_show.html.twig', [
            'post' => $post,
            'comments' => $commentsRepository->findBy(['post' => $post->getId()])
        ]);
    }

}
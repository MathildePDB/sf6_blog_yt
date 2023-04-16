<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/blog', name: 'app_blog_')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function indexPost(PostsRepository $postsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BLOG_ADMIN');

        $id = $this->getUser()->getId();
        
        $posts = $postsRepository->findBy(['user' => $id]);
        
        if (empty($posts)) {
            return $this->render('blog/index.html.twig', [
                'posts' => null
            ]);
        }

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/new', name: 'new')]
    public function newPost(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BLOG_ADMIN');

        $post = new Posts();

        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slug($post->getTitle());
            $post->setSlug($slug);

            $em->persist($post);
            $em->flush();

            $this->addFlash('succès', 'L\'article a bien été ajouté');

            return $this->redirectToRoute('app_blog_index');

        }

        return $this->render('blog/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function showPost(Posts $post): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BLOG_ADMIN');

        return $this->render('blog/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit')]
    public function editPost(Request $request, Posts $post, PostsRepository $postsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BLOG_ADMIN');
        
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postsRepository->save($post, true);
        
            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{slug}/delete', name: 'delete', methods: ['POST'])]
    public function deletePost(Request $request, Posts $post, PostsRepository $postsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getSlug(), $request->request->get('_token'))) {
            $postsRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Comments;
use App\Entity\Posts;
use App\Form\CategoriesType;
use App\Form\CommentsAdminType;
use App\Form\PostsType;
use App\Repository\CategoriesRepository;
use App\Repository\CommentsRepository;
use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function adminIndex()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig');
    }

    #[Route('/posts', name: 'posts')]
    public function adminPosts(PostsRepository $postsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/posts/index.html.twig', [
            'posts' => $postsRepository->findAll()
        ]);
    }

    #[Route('/post/{id}', name: 'posts_show')]
    public function adminPostsShow(Posts $post): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('admin/posts/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/post/{id}/edit', name: 'posts_edit')]
    public function adminPostsEdit(Request $request, Posts $post, PostsRepository $postsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postsRepository->save($post, true);
        
            return $this->redirectToRoute('app_admin_posts', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/posts/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);

    }

    #[Route('post/{id}', name: 'posts_delete', methods: ['POST'])]
    public function adminPostDelete(Request $request, Posts $post, PostsRepository $postsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postsRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_admin_posts', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/users', name: 'users')]
    public function adminUsers(UsersRepository $usersRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/users/index.html.twig', [
            'users' => $usersRepository->findAll()
        ]);
    }

    // #[Route('/users/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    // public function adminUsersEdit(): Response
    // {
    //       // code to edit only roles from user
    // }

    #[Route('/categories', name: 'categories')]
    public function adminCategories(CategoriesRepository $categoriesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll()
        ]);
    }

    #[Route('/categories/new', name: 'category_new')]
    public function newPost(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categorie = new Categories();

        $form = $this->createForm(CategoriesType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slug($categorie->getName());
            $categorie->setSlug($slug);

            $em->persist($categorie);
            $em->flush();

            $this->addFlash('succès', 'La catégorie a bien été ajoutée');

            return $this->redirectToRoute('app_admin_categories');

        }

        return $this->render('admin/categories/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/categories/{slug}', name: 'category_show')]
    public function showCategory(Categories $category): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/categories/show.html.twig', [
            'category' => $category
        ]);
    }

    #[Route('/categories/{slug}/edit', name: 'category_edit')]
    public function editCategory(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesRepository->save($category, true);
        
            return $this->redirectToRoute('app_admin_categories', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    #[Route('/categories/{slug}/delete', name: 'category_delete', methods: ['POST'])]
    public function deleteCategory(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$category->getSlug(), $request->request->get('_token'))) {
            $categoriesRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_admin_categories', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/comments', name: 'comments', methods: ['GET'])]
    public function index(CommentsRepository $commentsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/comments/index.html.twig', [
            'comments' => $commentsRepository->findAll(),
        ]);
    }

    #[Route('/comments/{id}/edit', name: 'comments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comments $comment, CommentsRepository $commentsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CommentsAdminType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentsRepository->save($comment, true);

            return $this->redirectToRoute('app_admin_comments', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/comments/{id}', name: 'comments_delete', methods: ['POST'])]
    public function delete(Request $request, Comments $comment, CommentsRepository $commentsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentsRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_admin_comments', [], Response::HTTP_SEE_OTHER);
    }

}
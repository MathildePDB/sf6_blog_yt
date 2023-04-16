<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'app_profile')]
class ProfileController extends AbstractController 
{
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        // show comments from app.user

        // if user = ROLE_BLOG_ADMIN ; show lasts posts

        return $this->render('profile/index.html.twig');
    }

    // function newPost where user_id = app.user.id

    // function editPost where user_id = app.user.id

    // function deletePost where user_id = app.user.id
}
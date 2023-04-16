<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $email, JWTService $jwt): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate user's jwt
            $header = ['typ' => 'JWT', 'alg' => 'HS256'];

            // create payload
            $payload = ['user_id' => $user->getId()];

            // generate token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // send an email
            $email->send('no-reply@site.com', $user->getEmail(), 'Activation de votre compte Blog sf6', 'register', ['user' => $user, 'token' => $token]);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            // recover payload
            $payload = $jwt->getPayload($token);

            // recover user from token
            $user = $usersRepository->find($payload['user_id']);

            // check profile not activated
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Utilisateur activé');

                return $this->redirectToRoute('app_profile_index');
            }
        }

        // if token is not valid or expired or corrupt
        $this->addFlash('danger', 'Le token est invalide ou a expiré');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/resendverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $email, UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');
            return $this->redirectToRoute('app_profile_index');
        } 

        // generate user's jwt
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];

        // create payload
        $payload = ['user_id' => $user->getId()];

        // generate token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // send an email
        $email->send('no-reply@site.com', $user->getEmail(), 'Activation de votre compte Blog sf6', 'register', ['user' => $user, 'token' => $token]);

        $this->addFlash('success', 'Email de vérification envoyé');

        return $this->redirectToRoute('app_profile_index');
    }
}

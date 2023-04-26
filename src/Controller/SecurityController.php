<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Security\LoginPageAuthenticator; // <-- Mettre à jour la référence
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    private LoginPageAuthenticator  $authenticator;

    public function __construct(LoginPageAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $lastAuthError = $this->authenticator->getLastAuthenticationError();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'last_auth_error' => $lastAuthError]);
    }

    #[Route(path: '/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(Request $request): void
    {
        $this->get('session')->clear();

        $targetUrl = $request->query->get('target');
        $response = new RedirectResponse($targetUrl ?: $this->generateUrl('app_login'));
        $response->send();
    }

    public function onLogoutSuccess(Request $request)
    {
        return new RedirectResponse($this->generateUrl('app_login'));
    }
}
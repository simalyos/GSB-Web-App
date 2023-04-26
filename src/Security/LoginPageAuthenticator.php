<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils as BaseAuthenticationUtils;
use Symfony\Component\Routing\RouterInterface;

class LoginPageAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';



    private UrlGeneratorInterface $urlGenerator;
    private AuthenticationUtils $authenticationUtils;

    public function __construct(UrlGeneratorInterface $urlGenerator, AuthenticationUtils $authenticationUtils, RouterInterface $router)
    {
        $this->urlGenerator = $urlGenerator;
        $this->authenticationUtils = $authenticationUtils;
        $this->router = $router;
    }


    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');
        $request->getSession()->set(Security::LAST_USERNAME, $username);
        
        // Récupérer la valeur de redirection souhaitée et la stocker dans la session
        $targetPath = $request->request->get('_target_path');
        if ($targetPath) {
            $request->getSession()->set('_security.'.$this->firewallName.'.target_path', $targetPath);
        }
    
        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('passwd', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
        $username = $request->request->get('username', '');

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('passwd', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function getLastAuthenticationError(): ?AuthenticationException
    {
        return $this->authenticationUtils->getLastAuthenticationError();
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetUrl = $this->router->generate('app_home');

        return new RedirectResponse($targetUrl);
        // if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        //     return new RedirectResponse($targetPath);
        // }

        // // For example:
        // // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    //public function supportsRememberMe(): bool
    //{
    //return true;
    //}

    public function onLogoutSuccess(Request $request)
    {
        if (!$this->getUser()) {
            // User is already logged out, redirect to login page
            $targetUrl = $this->urlGenerator->generate('app_login');
            return new RedirectResponse($targetUrl);
        }
    
        // Otherwise, continue with the default logout success behavior
        return parent::onLogoutSuccess($request);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
<?php
namespace App\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $roles = $token->getRoleNames();

        if (in_array('ROLE_ADMIN', $roles)) {
            $redirect = $this->router->generate('admin_dashboard');
        } else {
            $redirect = $this->router->generate('danceSchool_dashboard');
        }

        return new RedirectResponse($redirect);
    }
}

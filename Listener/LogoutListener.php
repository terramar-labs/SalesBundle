<?php

namespace TerraMar\Bundle\SalesBundle\Listener;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;

class LogoutListener implements LogoutHandlerInterface
{
    /**
     * This method is called by the LogoutListener when a user has requested
     * to be logged out. Usually, you would unset session variables, or remove
     * cookies, etc.
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $session = $request->getSession();
        $session->remove(AbstractController::CURRENT_OFFICE_KEY);
    }
}

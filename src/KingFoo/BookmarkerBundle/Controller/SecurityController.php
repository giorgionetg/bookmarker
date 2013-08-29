<?php

namespace KingFoo\BookmarkerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContextInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Template
     */
    public function loginAction()
    {
        $session = $this->get('session');

        // Check if there was a previous authentication attempt
        $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);

        return array('error' => $error);
    }

    /**
     * @Route("/logout", name="logout")
     * @Template
     */
    public function logoutAction()
    {
        // This action is never executed as it's intercepted by the security component.
    }

    /**
     * @Route("/authenticate", name="authenticate")
     * @Template
     */
    public function authenticateAction()
    {
        // This action is never executed as it's intercepted by the security component.
    }
}

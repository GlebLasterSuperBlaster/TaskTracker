<?php

namespace App\Controller;

use mysql_xdevapi\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     * @Template()
     */
    public function indexAction()
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('project_index');
        }
        return [

        ];
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $helper
     * @Template()
     * @return array
     */
    public function loginAction(AuthenticationUtils $helper)
    {
        return [
          'error' => $helper->getLastAuthenticationError()
        ];
    }

    /**
     * @Route("/logout", name="logout")
     * @throws \Exception
     */
    public function logoutAction() : void
    {
        throw new \Exception('Nothing to do here');
    }

}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Service\ErrorMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminController extends Controller
{

    /**
     * @Route("/admin", name="adminIndex")
     */
    public function adminIndexAction()
    {

        $user = $this->getUser();

        // Render admin index template
        return $this->render('admin/index.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/admin/documentation", name="documentation")
     */
    public function adminDocumentationAction()
    {
        $user = $this->getUser();

        // render template
        return $this->render('admin/documentation.html.twig', array(
            'user' => $user
        ));
    }

}

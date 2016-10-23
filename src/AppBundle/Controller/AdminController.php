<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    /**
     * @Route("/login/", name="login")
     * @Route("/signin/")
     * @Route("/sign-in/")
     */
    public function signInAction()
    {
        // render template
        return $this->render('admin/login.html.twig', array());
    }

}

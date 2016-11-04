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

    /**
     * @Route("/logout/", name="logout")
     * @Route("/log-out/")
     * @Route("/signout/")
     * @Route("/sign-out/")
     */
    public function signOutAction()
    {
        // todo destroy session

        // Take the user back to site index.
        return $this->redirectToRoute('index');


    }

    /**
     * @Route("/admin/", name="adminIndex")
     */
    public function adminIndexAction()
    {
        // todo check permissions

        // render template
        return $this->render('admin/index.html.twig', array());
    }

    /**
     * @Route("/admin/documentation/", name="documentation")
     */
    public function documentationAction()
    {
        // render template
        return $this->render('admin/documentation.html.twig', array());
    }





}

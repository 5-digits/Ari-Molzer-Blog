<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminController extends Controller
{

    /**
     * @Route("/login/", name="login")
     * @Route("/signin/")
     * @Route("/sign-in/")
     */
    public function signInAction(Request $request)
    {
        // render template
        // return $this->render('admin/login.html.twig', array());

        // Start the user session
        $session = $request->getSession();
        $session->start();

        // Find the user that was just signed in
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->findOneById(1);

        // Assign the signed in user object to the session
        $session->set('user', $user);

        // Redirect the user to the site index
        return $this->redirectToRoute('adminIndex');
    }

    /**
     * @Route("/logout/", name="logout")
     * @Route("/log-out/")
     * @Route("/signout/")
     * @Route("/sign-out/")
     */
    public function signOutAction()
    {
        // Destroy session
        $this->get('session')->invalidate();

        // Take the user back to site index.
        return $this->redirectToRoute('index');

    }

    /**
     * @Route("/admin/", name="adminIndex")
     */
    public function adminIndexAction()
    {

        $user = $this->get('session')->get('user');

        // Check there is a logged in user.
        // Redirect them back to the site index if they are not.
        if (!$user) {
            return $this->redirectToRoute('index');
        }

        // Check the user has privileges to access this route
        // Redirect them back to the site index if they do not.
        if ($user->getPrivilege() > User::PERMISSION_LEVEL_OWNER) {
            return $this->redirectToRoute('index');
        }

        // Render admin index template
        return $this->render('admin/index.html.twig', array(
            'user' => $user
        ));
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

<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 16/11/2016
 * Time: 12:25 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Form\LoginType;

class SessionController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Route("/signin")
     * @Route("/sign-in")
     */
    public function signInAction(Request $request)
    {

        // Get the current signed-in user and redirect them if they are
        // already signed in
        $user = $this->get('session')->get('user');
        if ($user) {
            $this->redirectToRoute('index');
        }

        //  Validate login form submission (will only happen on POST)
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);
        $formData = $form->getData();

        // Handle the form submit
        if ($form->isSubmitted()) {

            $email = $formData->getEmail();
            $passwordRaw = $formData->getPassword();

            // Find the user that was just signed in
            $repository = $this->getDoctrine()->getRepository('AppBundle:User');

            $user = $repository->findOneByEmail($email);

            if (!$user) {
                // No user registered with this email
            }

            if ($user->getPassword() == $passwordRaw) {
                // Incorrect password
            }

            // Start the user session
            $session = $request->getSession();
            $session->start();

            // Assign the signed in user object to the session
            $session->set('user', $user);

            // Take them to the site index
            $this->redirectToRoute('index');

        }

        // Check if a user is currently signed in
        // Redirect them to the site index if they are
        $user = $this->get('session')->get('user');
        if ($user) {
            return $this->redirectToRoute('index');
        } else {
            // render template
            return $this->render('admin/login.html.twig', array(
                'user' => null,
                'form' => $form->createView()
            ));
        }

    }

    /**
     * @Route("/logout", name="logout")
     * @Route("/log-out")
     * @Route("/signout")
     * @Route("/sign-out")
     */
    public function signOutAction()
    {
        // Destroy session
        $this->get('session')->invalidate();

        // Take the user back to site index.
        return $this->redirectToRoute('index');

    }

}
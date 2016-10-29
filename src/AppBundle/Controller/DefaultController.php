<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\BlogPost;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {

        $indexData = $this->loadIndexData();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'indexData' => $indexData,
        ));
    }


    /**
     * Get all the data to be used on the landing page
     * todo - move into an IndexDAO
     *
     * @return array
     */
    private function loadIndexData()
    {

        // get the most recent post
        $mostRecentBlog = $this->get('blogs')->getPosts(3);

        $indexData = array(
            'posts' => $mostRecentBlog
        );

        return $indexData;

    }

}

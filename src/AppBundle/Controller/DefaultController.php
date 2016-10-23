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
     *
     * Get all the data to be used on the landing page
     *
     * @return array
     */
    private function loadIndexData()
    {

        // instantiate array.
        $indexData = array();

        $quote = array(
            'message' => "If you prick us do we not bleed? If you tickle us do we not laugh? If you poison us do we not die? And if you wrong us shall we not revenge?",
            'author' => "William Shakespeare",
            'comment' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean id massa urna. Pellentesque lobortis nunc ut lorem imperdiet dignissim vitae dapibus sapien. Vestibulum pretium bibendum vulputate. Sed aliquam accumsan augue vitae elementum. In volutpat lectus vitae mi euismod commodo. Praesent eleifend quis augue vel gravida. Nulla eu euismod est, vel pharetra diam. Aliquam nec vehicula mi, et commodo enim. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce varius ultricies suscipit. Aenean rutrum sed magna non egestas. Cras hendrerit fringilla eros consectetur gravida.",
            'date' => '2 days ago'
        );


        //
        $mostRecentBlog = $this->get('blogs')->getMostRecent();

        $indexData = array(
            'mostRecentPost' => $mostRecentBlog,
            'quote' => $quote
        );

        return $indexData;

    }

}

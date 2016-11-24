<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SearchApiController extends Controller
{

    /**
     * @Route("/search/posts/by/term/{searchTerm}", name="searchTerm")
     * @Route("/search/posts/by/term", name="searchTermEndpoint")
     */
    public function searchByTermAction($searchTerm = null)
    {

        // Check if search term is valid and throw an error if its not
        if (!$searchTerm) {
            return new NotFoundHttpException("Please provide a valid search term.");
        }

        // Build the query to get 5 relevant objects
        // where the search term relates to the title or main body
        // todo - add in keywords and remove body
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT p.id, p.slug, p.title, p.subtitle, p.headerImage FROM AppBundle:Post p WHERE p.title LIKE :searchTerm OR p.body LIKE :searchTerm")
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->setMaxResults(5);

        // Run the query and get the results
        $searchResult = $query->getResult();

        // Define the post url - this cannot be done on the template dynamically
        $i = 0;
        foreach ($searchResult as $result) {
            // generate the url
            $url = $this->generateUrl('blogPost', array('slug' => $result['slug']));
            // add the url to the searchResults array
            $searchResult[$i]['url'] = $url;
            $i++;
        }

        // Create serializer
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        // Convert my blog post to json
        $jsonContent = $serializer->serialize($searchResult, 'json');

        // Return the Json content
        return new Response($jsonContent);

    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class APIController extends Controller
{

    /**
     * @Route("/api/v1/post/{id}", name="postApiById")
     */
    public function postApiByIdAction($id)
    {
        // check that id has been passed and hasn't got alphabetic chars
        if (!$id || !is_numeric($id)) {
            $this->createNotFoundException("No ID provided");
            return new Response(
                'Invalid Request - Please request a valid post ID',
                Response::HTTP_BAD_REQUEST,
                array('content-type' => 'application/json')
            );
        }

        // Get blog repository and the requested post
        $repository = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $post = $repository->findOneById($id);

        // Check if a valid post has been retrieved
        if (!$post) {
            $this->createNotFoundException("No post found");
            return new Response("No post found");
        }

        // Create serializer object
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        // Convert blog post to json and return json response
        $jsonContent = $serializer->serialize($post, 'json');
        return new Response($jsonContent);
    }

    /**
     * @Route("/api/v1/{object}/{id}", name="dynamicApiById")
     */
    public function dynamicApiByIdAction($object, $id)
    {

        // Access the requested object repository
        try {
            $repository = $this->getDoctrine()->getRepository('AppBundle:'.$object);
        } catch (\Exception $e) {
            return new Response(
                'The requested object type does not exist, please check the API documentation and try again.',
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!$id || !is_numeric($id)) {
            return new Response(
                'Invalid Request - Please request a valid object ID',
                Response::HTTP_BAD_REQUEST,
                array('content-type' => 'application/json')
            );
        }

        // todo - check if entity API_PUBLIC constant is set to true
        if (true) {
            $object = $repository->findOneById($id);
        } else {
            return new Response(
                'The requested object type is not publicly accessible.',
                Response::HTTP_FORBIDDEN
            );
        }

        // Check if a valid post has been retrieved
        if (!$object) {
            return $this->createNotFoundException("No objects found with the provided ID");
        }

        // Create serializer
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        // Convert my blog post to json
        $jsonContent = $serializer->serialize($object, 'json');

        return new Response($jsonContent);
    }

    /**
     * @Route("/api/v1/{object}/", name="dynamicApiAll")
     */
    public function dynamicApiAllAction($object)
    {

        // Access the requested object repository
        try {
            $repository = $this->getDoctrine()->getRepository('AppBundle:'.$object);
        } catch (\Exception $e) {
            return new Response(
                'The requested object type does not exist, please check the API documentation and try again.',
                Response::HTTP_BAD_REQUEST
            );
        }

        // todo - check if entity API_PUBLIC constant is set to true
        if (true) {
            $object = $repository->findAll();
        } else {
            return new Response(
                'The requested object type is not publicly accessible.',
                Response::HTTP_FORBIDDEN
            );
        }

        // Check a valid response has been retrieved
        if (!$object) {
            return $this->createNotFoundException("No objects found with the provided ID");
        }

        // Create serializer
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        // Convert my blog post to json
        $jsonContent = $serializer->serialize($object, 'json');

        return new Response($jsonContent);
    }

    /**
     * @Route("/api/v1/like/post/{id}/", name="likePostApi")
     * @Route("/api/v1/like/post/", name="likePostApiRoute")
     */
    public function likeBlogPostByIdAction($id) {

        // check that id has been passed and hasn't got alphabetic chars
        if (!$id || !is_numeric($id)) {
            $this->createNotFoundException("No ID provided");
            return new Response(
                'Invalid Request - Please request a valid post ID',
                Response::HTTP_BAD_REQUEST,
                array('content-type' => 'application/json')
            );
        }

        // Get blog repository and the requested post
        $repository = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $post = $repository->findOneById($id);

        // Check if a valid post has been retrieved
        if (!$post) {
            $this->createNotFoundException("No post found");
            return new Response("No post found");
        }

        // Create new like record - update post likes cache

        return New Response(
            "You have successfully liked this post",
            Response::HTTP_OK
        );


    }

}

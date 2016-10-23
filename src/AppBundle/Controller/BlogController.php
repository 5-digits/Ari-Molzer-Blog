<?php

namespace AppBundle\Controller;

use AppBundle\Form\BlogPostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\BlogPost;

class BlogController extends Controller
{

    /**
     * @Route("/blog/", name="blogIndex")
     */
    public function blogIndexAction(Request $request)
    {

        // get all blog posts
        // todo - limit to 8 per page
        $blogPosts = $this->get('blogs')->getAll();

        // handle a null response - will occur if there
        // are no blog posts in the database
        if ($blogPosts === null) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // render template
        return $this->render('blog/index.html.twig', array(
            'posts' => $blogPosts
        ));
    }

    /**
     * @Route("/blog/post/{slug}", name="blogPost")
     */
    public function viewBlogPostAction($slug)
    {

        // query for a single product by its primary key (usually "id")
        $repository = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $blogPost = $repository->findOneBySlug($slug);

        // handle invalid slug
        if ($blogPost === null) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // render template
        return $this->render('blog/read.html.twig', array(
            'post' => $blogPost
        ));
    }

    /**
     * @Route("/blog/new", name="blogNew")
     * @Route("/blog/add")
     * @Route("/blog/create")
     */
    public function newBlogAction(Request $request)
    {

        // todo add account validation

        // 1) build the form
        $newBlog = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $newBlog);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)

            $newBlog->setTitle($form->get('title'));
            $newBlog->setSubtitle($form->get('subtitle'));

            // validate the slug in the data access object
            $slug = $this->get('blogs')->validateSlug($form->get('slug'), $form->get('title'));
            $newBlog->setSlug($slug);

            $newBlog->setBody($form->get('body'));

            // 4) save the post!
            $em = $this->getDoctrine()->getManager();
            $em->persist($newBlog);
            $em->flush();

            // redirect them somewhere
            return $this->redirectToRoute('blogNew');
        }

        return $this->render(
            'blog/create.html.twig',
            array('form' => $form->createView())
        );
    }

}

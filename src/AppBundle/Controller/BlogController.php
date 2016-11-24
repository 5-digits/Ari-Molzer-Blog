<?php

namespace AppBundle\Controller;

use AppBundle\Util\DateHelper;
use AppBundle\Util\NavigationHelper;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use AppBundle\Form\BlogPostType;
use AppBundle\Util\StringHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Service\ErrorMessage;

class BlogController extends Controller
{

    /**
     * @Route("/blog/", name="blogIndex")
     */
    public function blogIndexAction()
    {
        // Get the current signed-in user
        $user = $this->get('session')->get('user');

        // get all blog posts
        $postsPerPage = 3;
        $blogPosts = $this->get('blogs')->getPosts($postsPerPage);

        // handle a null response - will occur if there
        // are no blog posts in the database
        if ($blogPosts === null || empty($blogPosts)) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // get pagination information if neccessary
        $postsCount = $this->get('blogs')->getNumberOfPosts();
        $pagination = NavigationHelper::getPaginationData($postsCount, 1, $postsPerPage);

        // render template
        return $this->render('blog/index.html.twig', array(
            'user' => $user,
            'posts' => $blogPosts,
            'pagination' => $pagination
        ));
    }

    /**
     * @param int $pageNumber
     * @return RedirectResponse|Response
     * @Route("/blog/page/{pageNumber}", name="blogPage", defaults={"pageNumber" = 1})
     */
    public function blogPageAction($pageNumber)
    {
        // validation to start page indexing from 1 not 0
        if ($pageNumber == 0 || empty($pageNumber)) {
            return $this->redirectToRoute('blogPage', array('pageNumber' => '1'));
        }

        // instantiate offset variable
        $offset = 0;

        // update the page offset if they are not on page 1
        // minus 1 from page number
        if ($pageNumber != 1) {
            ($offset = ($pageNumber - 1) * 4);
        }

        // get all blog posts, including the offset
        $blogPosts = $this->get('blogs')->getPosts(3, $offset);

        // handle an empty result response - will occur if there
        // are no blog posts in the database or if the page number
        // requested is invalid
        if (empty($blogPosts)) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // get pagination information if neccessary
        $postsCount = $this->get('blogs')->getNumberOfPosts();
        $pagination = NavigationHelper::getPaginationData($postsCount, $pageNumber, 4);

        // Get the current signed-in user
        $user = $this->get('session')->get('user');

        // render template
        return $this->render('blog/index.html.twig', array(
            'user' => $user,
            'posts' => $blogPosts,
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/blog/post/{slug}", name="blogPost")
     */
    public function viewBlogPostAction($slug)
    {

        // Get the current signed-in user
        $user = $this->get('session')->get('user');

        // query for a single product by its primary key (usually "id")
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $blogPost = $repository->findOneBySlug($slug);

        // Check if the post has been published for the public
        // Return a response if it hasn't
        if (!$blogPost->getPublished()) {
            return New Response(
                "This post is not publicly accessible at this time.",
                Response::HTTP_FORBIDDEN
            );
        }

        // Format date from datetime to readable
        $blogCreatedDate = DateHelper::formatDateDifference($blogPost->getCreated());

        // handle invalid slug
        if ($blogPost === null) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // render template
        return $this->render('blog/read.html.twig', array(
            'user' => $user,
            'post' => $blogPost,
            'dateCreated' => $blogCreatedDate
        ));
    }

    /**
     * @Route("/blog/post/id/{id}", name="blogPostById")
     */
    public function viewBlogPostIdAction($id)
    {
        // query for a single product by its primary key (usually "id")
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $blogPost = $repository->findOneById($id);

        // Check if the post has been published for the public
        // Return a response if it hasn't
        if (!$blogPost->getPublished()) {
            return New Response(
                "This post is not publicly accessible at this time.",
                Response::HTTP_FORBIDDEN
            );
        }

        // handle invalid id
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
    public function createBlogAction(Request $request)
    {

        // Get the current signed-in user
        $user = $this->get('session')->get('user');

        // Check the user has privileges to access this route
        if (!$user || $user->getPrivilege() < User::ROLE_ADMIN) {
            return New Response(
                'The signed-in user does not have permission to access this page',
                Response::HTTP_FORBIDDEN
            );
        }

        // Create a new blog post object and post form
        $newPost = new Post();
        $form = $this->createForm(BlogPostType::class, $newPost);

        // Handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Assign the form data to a variable for ease
            $newPost = $form->getData();

            // Set the blog object properties
            $newPost->setCreated(new \DateTime(date("Y-m-d H:i:s")));
            $newPost->setModified(new \DateTime(date("Y-m-d H:i:s")));

            // URLify the slug provided or URLify the title
            // if no custom slug was provided
            if ($newPost->getSlug()) {
                $newPost->setSlug(StringHelper::stringToUrl($newPost->getSlug()));
            } else {
                $newPost->setSlug(StringHelper::stringToUrl($newPost->getTitle()));
            }

            // Upload the image to the 'web/uploads/posts' directory
            $file = $newPost->getHeaderImage();

            // Create a name for our new file
            // Make it super unlikely to generate something not unique
            $newFileName = md5(uniqid('bpimage_', true)) . rand(10,100) . '.' . $file->guessExtension();
            $newPost->setHeaderImage($newFileName);

            // Upload this file into our selected uploads directory
            $file->move(
                $this->getParameter('posts_directory'),
                $newFileName
            );

            // Persist and save the post to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($newPost);
            $em->flush();

            // Redirect to the newly created post
            return $this->redirectToRoute('blogPost', array('slug' => $newPost->getSlug()));
        }

        // Handle a page request and render the template
        // with the new post form
        return $this->render('blog/create.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/blog/post/{slug}/update", name="blogPostUpdate")
     */
    public function updateBlogPostAction($slug)
    {
        // Get the current signed-in user
        $user = $this->get('session')->get('user');

        // Check the user is valid
        if (!$user) {
            $this->get('session')->getFlashBag()->add('warning', ErrorMessage::ERROR_NOT_SIGNED_IN);
            return $this->redirectToRoute('index');
        }

        // Check the user has valid permissions
        if ($user->getPrivilege() < User::PERMISSION_LEVEL_ADMIN) {
            $this->get('session')->getFlashBag()->add('warning', ErrorMessage::ERROR_INSUFFICIENT_PERMISSION);
            return $this->redirectToRoute('index');
        }

        // query for a single product by its primary key (usually "id")
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $blogPost = $repository->findOneBySlug($slug);

        // Check if the post has been published for the public
        // Return a response if it hasn't
        if (!$blogPost->getPublished()) {

            return New Response(
                "This post is not publicly accessible at this time.",
                Response::HTTP_FORBIDDEN
            );

        }

        // Format date from datetime to readable
        $blogCreatedDate = DateHelper::formatDateDifference($blogPost->getCreated());

        // handle invalid slug
        if ($blogPost === null) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // render template
        return $this->render('blog/read.html.twig', array(
            'user' => $user,
            'post' => $blogPost,
            'dateCreated' => $blogCreatedDate
        ));
    }

}

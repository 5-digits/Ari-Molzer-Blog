<?php

namespace AppBundle\Controller;

use AppBundle\Util\DateHelper;
use AppBundle\Util\NavigationHelper;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use AppBundle\Form\PostType;
use AppBundle\Util\StringHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Service\ErrorMessage;
use AppBundle\Entity\PostLike;

class BlogController extends Controller
{

    /**
     * @Route("/blog/", name="post_index")
     */
    public function postIndexAction()
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
     * @Route("/list/page/{pageNumber}", name="post_list_view", defaults={"pageNumber" = 1})
     */
    public function listPostAction($pageNumber)
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
     * @Route("/post/{id}/{slug}", name="post_read", requirements={"id": "\d+"}, defaults={"slug" = null})
     */
    public function viewPostAction($id, $slug)
    {

        // Get the current signed-in user
        $user = $this->getUser();

        // query for a single product by its primary key (usually "id")
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $blogPost = $repository->findOneById($id);

        // handle invalid slug
        if ($blogPost === null) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // Check if the post has been published for the public
        // Return a response if it hasn't
        if (!$blogPost->getPublished()) {
            return New Response(
                "This post is not publicly accessible at this time.",
                Response::HTTP_FORBIDDEN
            );
        }

        // Get the PostLike object
        $like = null;
        if ($user) {

            $like = $this->getDoctrine()
                ->getRepository('AppBundle:PostLike')
                ->findOneBy(Array(
                    'user' => $user,
                    'post' => $blogPost
                ));
        }

        // Format date from datetime to readable
        $blogCreatedDate = DateHelper::formatDateDifference($blogPost->getCreated());

        // render template
        return $this->render('blog/read.html.twig', array(
            'user' => $user,
            'post' => $blogPost,
            'like' => $like,
            'dateCreated' => $blogCreatedDate
        ));
    }

    /**
     * @Route("/post/new", name="post_create")
     */
    public function createPostAction(Request $request)
    {
        // Get the current signed-in user
        $user = $this->getUser();

        // Create a new blog post object and post form
        $newPost = new Post();
        $form = $this->createForm(PostType::class, $newPost);

        // Handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Assign the form data to a variable for ease
            $newPost = $form->getData();

            // Set the blog object properties
            $newPost->setAuthor($user);

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
            'title' => "New post",
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/post/edit/{id}", name="post_update")
     */
    public function updatePostAction(Request $request, $id)
    {
        // Get the current signed-in user
        $user = $this->getUser();

        // query for a single product by its primary key (usually "id")
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $blogPost = $repository->findOneById($id);

        // handle invalid slug
        if ($blogPost === null) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // Update the blog post object on form post
        $form = $this->createForm(PostType::class, $blogPost);

        // Handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Assign the form data to a variable for ease
            $updatePost = $form->getData();

            // URLify the slug provided or URLify the title
            // if no custom slug was provided
            if ($updatePost->getSlug()) {
                $updatePost->setSlug(StringHelper::stringToUrl($updatePost->getSlug()));
            } else {
                $updatePost->setSlug(StringHelper::stringToUrl($updatePost->getTitle()));
            }

            // Upload the image to the 'web/uploads/posts' directory
            $file = $updatePost->getHeaderImage();

            // Create a name for our new file
            // Make it super unlikely to generate something not unique
            $newFileName = md5(uniqid('bpimage_', true)) . rand(10,100) . '.' . $file->guessExtension();
            $updatePost->setHeaderImage($newFileName);

            // Upload this file into our selected uploads directory
            $file->move(
                $this->getParameter('posts_directory'),
                $newFileName
            );

            // Persist and save the post to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($updatePost);
            $em->flush();

            // Redirect to the newly created post
            return $this->redirectToRoute('post_read', array('id' => $updatePost->getId(), 'slug' => $updatePost->getSlug()));
        }

        // render template
        return $this->render('blog/create.html.twig', array(
            'user' => $user,
            'title' => "Update your post",
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/post/delete/{id}", name="post_delete")
     */
    public function deletePostAction(Request $request, $id)
    {
        $user = $this->getUser();

        // todo - soft delete
    }

    /**
     * @Route("/post/like/{postId}", name="post_like")
     */
    public function likePostAction($postId) {

        $user = $this->getUser();

        if (!$user) {
            return New JsonResponse(
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($postId);

        // Find if there is an existing like for this user
        $existingLike = $this->getDoctrine()
            ->getRepository('AppBundle:PostLike')
            ->findOneBy(Array(
                'user' => $user,
                'post' => $post
            ));

        $em = $this->getDoctrine()->getManager();

        // Create a new like if one does not exist for this user
        if (!$existingLike) {
            $newLike = new PostLike();
            $newLike->setUser($user);
            $em->persist($newLike);

        } else {
            // Toggle the existing objects value
            $updateValue = ($existingLike->getLiked() ? false  : true);
            $existingLike->setLiked($updateValue);
            $em->persist($existingLike);
        }

        // Flush the creation or update
        $em->flush();

        return New JsonResponse(
            JsonResponse::HTTP_OK
        );
    }

}

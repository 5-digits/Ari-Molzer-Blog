<?php

namespace AppBundle\Controller;

use AppBundle\Form\BlogPostType;
use AppBundle\Util\DateHelper;
use AppBundle\Util\NavigationHelper;
use AppBundle\Entity\BlogPost;
use AppBundle\Util\StringHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BlogController extends Controller
{

    /**
     * @Route("/blog/", name="blogIndex")
     */
    public function blogIndexAction()
    {

        // get all blog posts
        $blogPosts = $this->get('blogs')->getPosts(4);

        // handle a null response - will occur if there
        // are no blog posts in the database
        if ($blogPosts === null || empty($blogPosts)) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // get pagination information if neccessary
        $postsCount = $this->get('blogs')->getNumberOfPosts();
        $pagination = NavigationHelper::getPaginationData($postsCount, 1, 4);

        // render template
        return $this->render('blog/index.html.twig', array(
            'posts' => $blogPosts,
            'pagination' => $pagination
        ));
    }

    /**
     * @param int $pageNumber
     * @return RedirectResponse|Response
     * @Route("/blog/page/{pageNumber}", name="blogPage")
     */
    public function blogPageAction($pageNumber = 1)
    {
        // validation to start page indexing from 1 not 0
        if ($pageNumber == 0 || empty($pageNumber)) {
            return $this->redirectToRoute('blogPage', array('pageNumber' => '1'));
        }

        // define offset variable
        $offset = 0;

        // update the page offset if they are not on page 1
        // minus 1 from page number
        if ($pageNumber != 1) {
            ($offset = ($pageNumber - 1) * 4);
        }

        // get all blog posts, including the offset
        $blogPosts = $this->get('blogs')->getPosts(4, $offset);

        // handle an empty result response - will occur if there
        // are no blog posts in the database or if the page number
        // requested is invalid
        if (empty($blogPosts)) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // get pagination information if neccessary
        $postsCount = $this->get('blogs')->getNumberOfPosts();
        $pagination = NavigationHelper::getPaginationData($postsCount, $pageNumber, 4);

        // render template
        return $this->render('blog/index.html.twig', array(
            'posts' => $blogPosts,
            'pagination' => $pagination
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

        // Format date from datetime to readable
        $blogCreatedDate = DateHelper::formatDateDifference($blogPost->getCreated());

        // handle invalid slug
        if ($blogPost === null) {
            return new RedirectResponse($this->generateUrl('index'));
        }

        // render template
        return $this->render('blog/read.html.twig', array(
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $blogPost = $repository->findOneById($id);

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

        // todo - add user validation

        $newPost = new BlogPost();

        $form = $this->createFormBuilder($newPost)
            ->add('title', TextType::class, array(
                'attr' => array('class' => 'tinymce')
            ))
            ->add('subtitle', TextType::class)
            ->add('shortDescription', TextType::class)
            ->add('slug', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'another-wonderful-sunny-day'
            )))
            ->add('body', TextareaType::class, array(
                'attr'=> array(
                    'class' => 'materialize-textarea',
                    'placeholder' => 'Upload a post hero image'
                )
            ))
            ->add('headerImage', FileType::class, array('label' => 'Header Image'))

            ->add('submit', SubmitType::class, array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'btn waves-effect waves-light'
                )
            ))
            ->getForm();

        // Handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Assign the form data to a variable for ease
            $newPost = $form->getData();

            // Set the blog object properties
            // todo - update entity created and modified fields
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

            // Make sure no two images have the same ID and loop through until
            // the name generated is unique.
            $existingImageExists = true;
            while ($existingImageExists) {

                // Create a name for our new file
                // Make it super unlikely to generate something not unique
                $newFileName = md5(uniqid('bpimage_', true)) . rand(10,100) . '.' . $file->guessExtension();

                // Search for an existing image with the name name
                $repository = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
                $existingBlogPost = $repository->findOneByHeaderImage($newFileName);

                // Check if the new file name is already in use
                if (!$existingBlogPost) {
                    continue;
                }

                $existingImageExists = false;
            }

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
            'form' => $form->createView()
        ));
    }

}

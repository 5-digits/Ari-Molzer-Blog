<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 19/10/2016
 * Time: 8:43 AM
 */

namespace AppBundle\Dao;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostLike;
use AppBundle\Util\StringHelper;
use Doctrine\Bundle\DoctrineBundle\Registry;

class BlogDao
{

    /** @var Registry */
    private $doctrine;

    /**
     * BlogDao constructor.
     *
     * @param Registry $doctrine
     */
    function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param bool $publishedOnly
     * @return Post[]|null
     */
    function getPosts($limit = 10, $offset = 0, $publishedOnly = true)
    {
        $em = $this->doctrine->getManager();

        if ($publishedOnly) {
            $blogPost = $em->getRepository('AppBundle:Post')->findBy(
                array('published' => true),
                array('id' => 'DESC'),
                $limit,
                $offset
            );
        } else {
            $blogPost = $em->getRepository('AppBundle:Post')->findBy(
                array(),
                array('id' => 'DESC'),
                $limit,
                $offset
            );
        }

        return $blogPost;
    }

    /**
     * Count the number of posts in the database
     * By default, only get published posts
     *
     * @param bool $publishedOnly
     * @return int
     */
    function getNumberOfPosts($publishedOnly = true)
    {
        // Generate the query depending on method parameter
        if (!$publishedOnly) {

            // Get the number of all blog posts in the database
            $query = $this->doctrine->getManager()->createQueryBuilder()
                ->select('COUNT(bp.id)')
                ->from('AppBundle:Post', 'bp')
                ->getQuery();

        } else {

            // Get only the posts that are public
            $query = $this->doctrine->getManager()->createQueryBuilder()
                ->select('COUNT(bp.id)')
                ->from('AppBundle:Post', 'bp')
                ->where('bp.published = 1')
                ->getQuery();
        }

        // Return query results
        return $query->getSingleScalarResult();
    }

    /**
     * Count the number of posts in the database
     * By default, only get published posts
     *
     * @param PostLike $post
     * @return int
     */
    function getNumberOfLikesByPost($post) {

        $query = $this->doctrine->getManager()->createQueryBuilder()
            ->select('COUNT(pl)')
            ->from('AppBundle:PostLike', 'pl')
            ->where('pl.liked = :post')
            ->setParameter('post', $post)
            ->getQuery();

        // Return query results
        return $query->getSingleScalarResult();
    }


    /**
     * if the user provides a slug, return this value
     * else use sanitize the title as use this as the slug
     *
     * @param $slug
     * @param $title
     * @return string
     */
    function validateSlug($slug = null, $title = null)
    {
        // validate method parameters
        if (!$slug && !$title) {
            throw new \InvalidArgumentException("Method requires either a slug or title parameter");
        }

        // if the invocation has provided a slug, return this.
        if ($slug) {
            return $slug;
        }

        // convert title string to valid url style string
        $titleAsSlug = StringHelper::stringToUrl($title);

        return $titleAsSlug;
    }
}
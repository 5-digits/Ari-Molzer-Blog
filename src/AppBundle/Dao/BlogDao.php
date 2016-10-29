<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 19/10/2016
 * Time: 8:43 AM
 */

namespace AppBundle\Dao;

use AppBundle\Entity\BlogPost;
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
     * @return BlogPost[]|null
     */
    function getPosts($limit = 10, $offset = 0)
    {
        $em = $this->doctrine->getManager();

        $blogPost = $em->getRepository('AppBundle:BlogPost')->findBy(
            array(),
            array('id' => 'DESC'),
            $limit,
            $offset
        );

        return $blogPost;
    }

    function getNumberOfPosts()
    {
        $query = $this->doctrine->getManager()->createQueryBuilder()
            ->select('COUNT(bp.id)')
            ->from('AppBundle:BlogPost', 'bp')
            ->where('bp.published = 1')
            ->getQuery();

        $total = $query->getSingleScalarResult();

        return $total;
    }


    /**
     *
     * if the user provides a slug, return this value
     * else use sanitize the title as use this as the slug
     *
     * @param $slug
     * @param $title
     * @return string
     *
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
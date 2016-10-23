<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 19/10/2016
 * Time: 8:43 AM
 */

namespace AppBundle\Dao;

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

    function getMostRecent()
    {
        $em = $this->doctrine->getManager();

        $blogPost = $em->getRepository('AppBundle:BlogPost')->findOneBy(
            array(),
            array('id' => 'DESC')
        );

        return $blogPost;
    }

    function getAll()
    {
        $em = $this->doctrine->getManager();

        $allPosts = $em->getRepository('AppBundle:BlogPost')->findAll();

        return $allPosts;
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
    function validateSlug($slug, $title)
    {

        // validate method
        if (!$slug && !$title) {
            throw new \InvalidArgumentException("Method requires either a slug or title parameter");
        }

        // if the user provided a slug, return this.
        if ($slug) {
            return $slug;
        }

        // validate title string to valid url style string
        $titleAsSlug = StringHelper::stringToUrl($title);

        return $titleAsSlug;
    }
}
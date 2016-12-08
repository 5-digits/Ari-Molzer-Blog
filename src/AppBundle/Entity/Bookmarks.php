<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 7/11/2016
 * Time: 8:20 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_bookmark")
 */
class Bookmark
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="bookmark")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * When created, this field will be true, but can be made false.
     * In that case we keep the record, even though it is now void.
     * @ORM\Column(name="bookmarked", type="boolean", options={"default" : 1})
     */
    private $bookmarked = 1;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @ORM\Column(name="date_modified", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $modified;
}
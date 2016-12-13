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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_bookmark")
 */
class PostBookmark
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="PostLike")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="PostLike")
     * @ORM\JoinColumn(name="Post", referencedColumnName="id")
     */
    private $post;

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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getBookmarked()
    {
        return $this->bookmarked;
    }

    /**
     * @param mixed $bookmarked
     */
    public function setBookmarked($bookmarked)
    {
        $this->bookmarked = $bookmarked;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param mixed $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    }

}
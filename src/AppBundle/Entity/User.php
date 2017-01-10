<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 19/10/2016
 * Time: 6:49 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{

    // Define user permission levels and their usages
    const ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_USER = "ROLE_USER";
    const ROLE_STEALTH_BANNED = "ROLE_STEALTH_BANNED";
    const ROLE_BANNED = "ROLE_BANNED";

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="firstname", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     min=3,
     *     max=100,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $firstname;

    /**
     * @ORM\Column(name="surname", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     min=3,
     *     max=100,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $surname;

    /**
     * @ORM\Column(name="google_plus_id", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     min=10,
     *     max=100,
     *     minMessage="The code is too short.",
     *     maxMessage="The code is too long.",
     *     groups={"Profile"}
     * )
     */
    protected $googlePlusId;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @ORM\Column(name="date_modified", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $modified;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->roles = array(self::ROLE_USER);
    }

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
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getGooglePlusId()
    {
        return $this->googlePlusId;
    }

    /**
     * @param mixed $googlePlusId
     */
    public function setGooglePlusId($googlePlusId)
    {
        $this->googlePlusId = $googlePlusId;
    }

    /**
     * @return mixed
     */
    public function getIsGooglePlusAccountPublic()
    {
        return $this->isGooglePlusAccountPublic;
    }

    /**
     * @param mixed $isGooglePlusAccountPublic
     */
    public function setIsGooglePlusAccountPublic($isGooglePlusAccountPublic)
    {
        $this->isGooglePlusAccountPublic = $isGooglePlusAccountPublic;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @return string
     */
    public function getFormattedFullName()
    {
        return $this->firstname . " " . $this->surname;
    }

}

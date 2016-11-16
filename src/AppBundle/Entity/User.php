<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 19/10/2016
 * Time: 6:49 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */

class User
{

    // Define user permission levels and their usages
    // See the manage permission page for details - todo - make manage permission page
    const PERMISSION_LEVEL_OWNER = 400;
    const PERMISSION_LEVEL_ADMIN = 300;
    const PERMISSION_LEVEL_USER = 200;
    const PERMISSION_LEVEL_STEALTH_BANNED = 100;
    const PERMISSION_LEVEL_BANNED = 000;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="firstname", type="string", length=100, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(name="surname", type="string", length=100, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", length=100, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(name="privilege", type="string", length=20)
     */
    private $privilege = self::PERMISSION_LEVEL_USER;

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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * @param mixed $privilege
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

}
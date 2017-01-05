<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 5/1/17
 * Time: 5:10 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="site_setting")
 */
class SiteSetting
{
    const SETTING_OWNER = "SITE_OWNER";
    const SETTING_SITE_NAME = "SITE_NAME";
    const SETTING_GOOGLE_ANALYTICS_CODE = "SITE_GA_CODE";
    const SETTING_GOOGLE_TAG_MANAGER_CODE = "SITE_GTM_CODE";

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="setting", type="string")
     */
    private $setting;

    /**
     * @ORM\Column(name="value", type="string")
     */
    private $value;

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
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * @param mixed $setting
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

}
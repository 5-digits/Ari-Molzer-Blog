<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 24/11/2016
 * Time: 4:08 PM
 */

namespace AppBundle\Service;


class ErrorMessage
{
    const ERROR_NOT_SIGNED_IN = "Please sign in to the site to access this page";
    const ERROR_INSUFFICIENT_PERMISSION = "You are not able to access this page with your current permissions, speak to the site owner(s) to revise your permissions.";

    //
    const ERROR_POST_NOT_PUBLIC = "This post has not been made public - please contact the site owner or revisit this page later.";
}